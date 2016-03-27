<?php namespace Tranquil\Database;

/**
 * undocumented class
 *
 * @package default
 **/
class Adapter_Mysql implements Adapter_Interface
{
	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getDsn(array $config)
	{
		extract($config);

		$port = isset($port) ? "port={$port};" : '';
		$unix = isset($unix_socket) ? "unix_socket={$unix_socket};" : '';

		$dsn = "mysql:host={$host};dbname={$database};";
		$dsn .= $port;
		$dsn .= $unix;

		return $dsn;
	}
} // END class Adapter_Mysql implements Adapter_Interface