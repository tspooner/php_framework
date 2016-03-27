<?php namespace Tranquil;

use Tranquil\Core\Panacea;
use Tranquil\Benchmark\Timer;
use Tranquil\Network\Request;
use Tranquil\Network\Environment;
use Tranquil\Configure\ConfigurationManager as Config;
use Tranquil\Security\SimpleEncrypter as Encrypter;

/**
*
*/
class App
{
	public static $panacea;

	protected $_utilities = array();

	protected $_configuration = null;

	public $timer = null;

	public final function __construct()
	{
		self::$panacea = Panacea::getInstance();

		$this->_configuration = Config::getInstance();

		// self::$panacea->define('env', '\\Tranquil\\Network\\Environment', true)
		// 	->instance();

		self::$panacea->define('req', '\\Tranquil\\Network\\Request', true)
			->instance();
	}

	public function run()
	{
		var_dump(self::$panacea->instance('req')->params());
	}

	public function end()
	{

	}
}