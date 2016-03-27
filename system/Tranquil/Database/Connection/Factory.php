<?php namespace Tranquil\Database;

/**
 * undocumented class
 *
 * @package default
 **/
class Connection_Factory
{
	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function forge(Adapter_Interface $adapter, array $config, $name = null)
	{
		$username = isset($config['username']) ? $config['username'] : $config['user'];
		$password = isset($config['password']) ? $config['password'] : $config['pass'];

		try
		{
			$con = new Connection(
				$adapter->getDsn($config),
				$username,
				$password,
				$config['options']
			);

			$con->setAttribute(Connection::ATTR_CONNECTION_NAME, $name);

			if (isset($config['fetch'])) $con->setFetchMode($config['fetch']);
		}
		catch (DbException $e)
		{

		}

		return $con;
	}
} // END class Connection_Factory