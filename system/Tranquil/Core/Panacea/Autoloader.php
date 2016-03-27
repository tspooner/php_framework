<?php namespace Tranquil\Core;

/**
 *
 */
class Panacea_Autoloader
{
	/**
	 * Defines whether the class loader has been instantiated
	 *
	 * @var bool
	 **/
	private static $_instantiated = false;

	/**
	 * [__construct description]
	 **/
	private function __construct() {}

	/**
	 * [instantiate description]
	 *
	 * @return [type] [description]
	 **/
	public static function register()
	{
		if (!self::$_instantiated)
		{
			spl_autoload_register(array('Tranquil\Core\Panacea_Autoloader', 'load'));

			static::$_instantiated = true;
		}
	}

	public static function load($class)
	{
		$name = static::getClassName($class);

		$path = static::getPath($class);

		$path = SYSTEM . $path;

		if (file_exists($path))
		{
			return require_once $path;
		}
	}

	private static function getPath($class)
	{
		if ($class[0] == '\\') $class = substr($class, 1);
		return str_replace(array('\\', '_'), DS, $class) . '.php';
	}

	private static function getClassName($class)
	{
		return array_last(explode('\\', $class));
	}
}