<?php namespace Tranquil\Core;

/**
*
*/
abstract class Singleton
{
	protected static $_instances = [];

	abstract protected function __construct();

	public static function getInstance()
	{
		$class = get_called_class();

		if (isset(static::$_instances[$class]) && (static::$_instances[$class] instanceof self)){
			return static::$_instances[$class];
		}

		return static::$_instances[$class] = new $class();
	}
}