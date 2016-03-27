<?php namespace Tranquil\Database;

/**
 * undocumented class
 *
 * @package default
 **/
class Archetype
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected static $_factory;

	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	protected static $_config;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected static $_defaultName = 'default';

	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	protected static $_adapters = array();

	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	protected static $_connections = array();

	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	protected static $_dbMaps = array();

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __construct($config)
	{
		static::$_config = $config;
		static::$_factory = new Connection_Factory;
	}
	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function getDbMap($name = null)
	{
		if (null === $name)
		{
			$name = static::getDefaultName();
		}

		if (!isset(static::$_dbMaps[$name]))
		{
			static::$_dbMaps = new Map_Database($name);
		}

		return static::$_dbMaps[$name];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function setDbMap($name = null, Map_Database $dbMap)
	{
		if (null === $name)
		{
			$name = static::getDefaultName();
		}

		static::$_dbMaps[$name] = $dbMap;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function connection($name = null, $config = array())
	{
		if (null === $name)
		{
			$name = static::getDefaultName();
		}

		$config = array_merge(static::$_config[$name], $config);

		if (!isset(static::$_connections[$name]))
		{
			if (empty($config))
			{
				throw new DbException("Incorrect configuration for the connection ['{$name}']");
			}

			$con = static::makeConnection($name, $config);
			static::setConnection($name, $con);
		}

		return static::$_connections[$name];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function setConnection($name, Connection $con)
	{
		static::$_connections[$name] = $con;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function makeConnection($name, $config)
	{
		$adapter = static::getAdapter($config['adapter']);

		return static::$_factory->forge($adapter, $config, $name);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function getAdapter($adapter)
	{
		$adapter = __NAMESPACE__ . '\\Adapter_' . ucfirst(strtolower($adapter));

		return new $adapter();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function getDefaultName()
	{
		return static::$_defaultName;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function table($name, $connection = null)
	{
		return static::connection($connection)->table($name);
	}




















} // END class Archetype