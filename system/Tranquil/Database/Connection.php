<?php namespace Tranquil\Database; use \PDO, \Closure;

/**
 * undocumented class
 *
 * @package default
 **/
class Connection extends PDO
{
	const ATTR_CONNECTION_NAME = -2;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $fetchMode = PDO::FETCH_ASSOC;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_adapter;

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __construct($dsn, $username, $password, $options)
	{
		$this->_adapter = explode(':', $dsn)[0];
		parent::__construct($dsn, $username, $password, $options);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function prepareBindings($bindings)
	{
		return array_flatten($bindings);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function run($sql, $bindings, Closure $callback)
	{
		try
		{
			$result = $callback($this, $sql, $bindings);
		}
		catch (\PDOException $e)
		{
			$exception = new DbException($e->getMessage());
			$exception->setCustom($sql);
			throw $exception;
		}

		return $result;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function create($sql, $bindings)
	{
		$this->statement($sql, $bindings);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function read($sql, $bindings)
	{
		return $this->run($sql, $bindings, function ($me, $sql, $bindings)
		{
			$stmt = $me->prepare($sql);

			$stmt->execute($me->prepareBindings($bindings));

			return $stmt->fetchAll($me->getFetchMode());
		});
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function update($sql, $bindings)
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function delete($sql, $bindings)
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function statement($query, $bindings = array())
	{
		return $this->run($query, $bindings, function($me, $query, $bindings)
		{
			$bindings = $me->prepareBindings($bindings);

			return $me->prepare($query)->execute($bindings);
		});
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function newQuery()
	{
		return new Query($this);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function adapter()
	{
		return ucfirst(strtolower($this->_adapter));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function table($name)
	{
		return $this->newQuery()->from($name);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getFetchMode()
	{
		return $this->fetchMode;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setFetchMode($mode)
	{
		$this->fetchMode = $mode;
	}
} // END class Connection extends PDO