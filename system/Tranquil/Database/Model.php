<?php namespace Tranquil\Database; use \Closure;

use \Tranquil\Utilities\Inflector;
use \Tranquil\Utilities\Collection;

/**
 * undocumented class
 *
 * @package default
 **/
abstract class Model
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_connection = 'default';

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_name;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_pK = 'id';

	//
	const CREATED = 'created_at';

	//
	const UPDATED = 'updated_at';

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __construct(array $attributes = array())
	{
		if (!isset($this->_name))
		{
			$this->setTableName(get_real_class($this), true);
		}

		if (!empty($attributes))
		{
			$this->fill($attributes);
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function fill(array $attributes)
	{
		foreach ((array) $attributes as $key => $val)
		{
			$this->$key = $val;
		}

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getAttributes()
	{
		$remove = array(
			'_connection',
			'_name',
			'_pK'
		);

		$attributes = get_object_vars($this);

		foreach ($attributes as $key => $val)
		{
			if (in_array($key, $remove))
				unset($attributes[$key]);
		}

		return $attributes;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function makeCollection(Closure $queryCallback)
	{
		$results = $queryCallback($this);
		$resources = array();
		$i = 0;

		foreach ($results as $result)
		{
			$resources[$i] = $this->makeClone()->fill($result);

			$i++;
		}

		return new Collection($resources);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function all()
	{
		$me = new static;

		return $me->makeCollection(function ($me)
		{
			return $me->newQuery()->get();
		});
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function find($id = 1)
	{
		$me = new static;

		return $me->fill($me->newQuery()->where($me->_pK, $id)->get());
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function findBy($column, $value)
	{
		$me = new static;

		return $me->fill(
			$me->newQuery()->where($column, $value)->first()
		);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function findAllBy($column, $value)
	{
		$me = new static;

		return $me->makeCollection(function ($me) use ($column, $value)
		{
			return $me->newQuery()->where(strtolower($column), $value)->get();
		});
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function save(array $options = array())
	{
		$query = $this->newQuery();

		return $this->doInsert($query);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function newQuery()
	{
		$query = new Query($this->connection());

		return $query->from($this->_name);
	}

	/**
	 * undocumented function
	 *
	 * @return bool
	 **/
	public function doInsert(Query $query)
	{
		return $query->insert($this->getAttributes());
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function connection()
	{
		if (!$this->_connection instanceof _connection)
		{
			return Archetype::connection($this->_connection);
		}

		return $this->_connection;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setTableName($_name, $inflect = false)
	{
		if ($inflect)
			$_name = Inflector::pluralize($_name);

		$this->_name = $_name;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getTableName()
	{
		return $this->_name;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getConnection()
	{
		return $this->_connection();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function makeClone()
	{
		$model = clone $this;

		return $model;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __clone()
	{}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __call($method, $args)
	{
		if (strpos($method, 'findAllBy') !== FALSE)
		{
			return $this->findAllBy(str_replace('findAllBy', '', $method), $args[0]);
		}

		if (strpos($method, 'findBy') !== FALSE)
		{
			return $this->findBy(str_replace('findBy', '', $method), $args[0]);
		}

		if (!method_exists($this, $method))
		{
			$query = new Query($this->get_connection());
			$query->from($this->getTable_name());

			if (method_exists($query, $method))
			{
				return call_user_func_array(array($query, $method), $args);
			}
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function __callStatic($method, $args)
	{
		$instance = new static;

		return call_user_func_array(array($instance, $method), $args);
	}
} // END abstract class Model