<?php namespace Tranquil\Security;

use Tranquil\Configure\Configure;
use Tranquil\Error\ExceptionHandler;

class EncryptionException extends ExceptionHandler {}

abstract class Encrypter
{
	protected $_cipher = 'rijndael-256';
	protected $_mode = 'cbc';
	protected $_block = 32;

	protected $_key = 'loremips';

	abstract public function encrypt($plaintext);

	abstract public function decrypt($payload);

	abstract protected function hash($iv, $value);

	public function __construct()
	{
		$config = Configure::get('security');

		$this->key($config['key']);
	}

	protected function getPayload($payload)
	{
		$payload = json_decode(base64_decode($payload), true);

		if (!$payload || !$this->checkIntegrity($payload)) {
			throw new EncryptionException('No payload integrity!');
		}

		if (base64_decode($payload['mac']) !== $this->hash($payload['iv'], $payload['value'])) {
			throw new EncryptionException('Invalid HMAC!');
		}

		return $payload;
	}

	protected function checkIntegrity($payload)
	{
		return !isset($data['iv']) || !isset($data['value']) || !isset($data['mac']);
	}

	protected function iv()
	{
		$blockSize = $this->ivSize();
		$randomizer = $this->randomizer();
		$iv = mcrypt_create_iv($blockSize, $randomizer);

		return $iv;
	}

	protected function ivSize()
	{
		return mcrypt_get_block_size($this->_cipher, $this->_mode);
	}

	protected function randomizer()
	{
		if (defined('MCRYPT_DEV_URANDOM')) return MCRYPT_DEV_URANDOM;

		if (defined('MCRYPT_DEV_RANDOM')) return MCRYPT_DEV_RANDOM;

		mt_srand();

		return MCRYPT_RAND;
	}

	protected function addPadding($value)
	{
		$pad = $this->_block - (strlen($value) % $this->_block);

		return $value . str_repeat(chr($pad), $pad);
	}

	protected function stripPadding($value)
	{
		$pad = ord($value[($len = strlen($value)) - 1]);

		return $this->paddingIsValid($pad, $value) ? substr($value, 0, strlen($value) - $pad) : $value;
	}

	protected function paddingIsValid($pad, $value)
	{
		$beforePad = strlen($value) - $pad;

		return substr($value, $beforePad) == str_repeat(substr($value, -1), $pad);
	}

	protected function secureCompare($a, $b)
	{
		if (strlen($a) !== strlen($b)) {
			return false;
		}

		$result = 0;
		for ($i=0; $i < strlen($a); $i++) {
			$result |= ord($a[$i]) ^ ord($b[$i]);
		}
		return $result == 0;
	}

	protected function key($key = null)
	{
		is_null($key) ?
			$this->_key :
			$this->_key = base64_encode($key);
		return $this->_key;
	}
}