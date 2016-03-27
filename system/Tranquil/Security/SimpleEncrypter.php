<?php namespace Tranquil\Security;

use PHPSecLib\Crypt_AES;
use PHPSecLib\Crypt_Hash;

/**
*
*/
class SimpleEncrypter extends Encrypter
{
	protected $_encrypter;
	protected $_hasher;

	public function __construct()
	{
		parent::__construct();

		$this->_encrypter = new Crypt_AES();
		$this->_hasher = new Crypt_Hash('sha256');

		$this->_encrypter->enableContinuousBuffer();
	}

	public function encrypt($plaintext)
	{
		$iv = $this->iv();
		$this->_encrypter->setIV($iv);
		$this->_encrypter->setKey($this->_key);

		$value = $this->addPadding($plaintext);
		$value = $this->_encrypter->encrypt($plaintext);
		$value = base64_encode($value);

		$mac = base64_encode($this->hash($iv = base64_encode($iv), $value));

		$payload = base64_encode(json_encode(compact('iv', 'value', 'mac')));

		return $payload;
	}

	public function decrypt($payload)
	{
		$payload = $this->getPayload($payload);
		$value = base64_decode($payload['value']);
		$iv = base64_decode($payload['iv']);

		$this->_encrypter->setKey($this->key());
		$this->_encrypter->setIV($iv);

		$plaintext = $this->stripPadding($this->_encrypter->decrypt($value));

		if (!$plaintext) {
			throw new EncryptionException('Invalid key');
		}

		return $plaintext;
	}

	public function hash($iv, $value)
	{
		$this->_hasher->setKey($iv);
		return $this->_hasher->hash($value);
	}
}