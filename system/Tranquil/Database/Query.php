<?php namespace Tranquil\Database;

use \Tranquil\Utilities\Inflector;

/**
 * undocumented class
 *
 * @package default
 **/
class Query
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_connection;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_syntax;

	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	protected $_operators = array(
		'=', '>', '<', '>=', '<=', '<>', '!=',
		'LIKE', 'NOT LIKE', 'BETWEEN', 'ILIKE'
	);

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $bindings = array();

	// The table name
	public $table = null;

	// The table name
	public $insert = null;

	// The required columns
	public $columns = null;

	// The required columns
	public $where = null;

	// The required columns
	public $having = null;

	// The required columns
	public $order = null;

	// The required columns
	public $group = null;

	// The required columns
	public $limit = null;

	// The required columns
	public $offset = null;

	// The required columns
	public $aggregate = null;

	// The required columns
	public $join = null;

	// The required columns
	public $unions = array();

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __construct(Connection $connection)
	{
		$this->_connection = $connection;

		$class = '\\Tranquil\\Database\\Query\\Syntax_' . $this->connection()->adapter();

		$this->_syntax = new $class();

		$this->reset();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function reset($all = false)
	{
		$this->table = null;
		if (!$all) $this->component('columns', true);
		$this->where = null;
		$this->limit = null;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function from($name, $alias = null)
	{
		$alias = !is_null($alias) ? $alias : ucfirst(strtolower(Inflector::singularize($name)));

		$this->component('table')
			->setName($name)
			->setAlias($alias);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function get()
	{
		$args = array_flatten(func_get_args());

		if (!empty($args)) $this->select($args);

		$results = $this->runSelect();

		if (empty($results)) return FALSE;

		return count($results) > 1 ? $results : $results[0];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function all()
	{
		return $this->get(func_get_args());
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function first()
	{
		$this->take(1);

		return $this->get();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function pull($column)
	{
		return $this->get()[$column];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function lists()
	{
		$list = array();
		$args = func_get_args();
		$i = 0;

		foreach ($this->get() as $row)
		{
			foreach ($args as $arg)
			{
				$list[$i][$arg] = $row[$arg];
			}

			$i++;
		}

		return $list;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function distinct()
	{
		$this->component('columns')
			->setDistinct(true);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function take($limit)
	{
		$this->component('limit')
			->setLimit($limit);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function skip($offset)
	{
		$this->component('limit')
			->setOffset($offset);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function select()
	{
		$args = array_flatten(func_get_args());

		if (empty($args)) $args = array('*');

		$this->component('columns')
			->setColumns($args);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function andSelect()
	{
		$args = array_flatten(func_get_args());

		$this->component('columns')
			->addColumns($args);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function where($column, $operator, $value = null, $join = 'AND', $type = 'BASIC')
	{
		if (!in_array($operator, $this->_operators))
		{
			$value = $operator;

			$operator = '=';
		}

		$this->component('where')
			->addWhere($column, $operator, $value, $join, $type);

		$this->bindings[] = $value;

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function andWhere($column, $operator, $value = null)
	{
		$this->where($column, $operator, $value);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function orWhere($column, $operator, $value = null)
	{
		$this->where($column, $operator, $value, 'OR');

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function whereIn($column, $operator, $value = null, $join = 'AND')
	{
		$type = 'IN';

		return $this->where($column, $operator, $value, $join, $type);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function whereNotIn($column, $operator, $value = null, $join = 'AND')
	{
		$type = 'NOT IN';

		return $this->where($column, $operator, $value, $join, $type);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function whereBetween($column, $first, $last, $join = 'AND')
	{
		$type = 'BETWEEN';

		if (!is_array($first))
		{
			$first = (array) $first;
			$first[] = $last;
		}

		$this->where($column, '=', $first, $join, $type);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function whereNull()
	{
		$args = func_get_args();

		foreach ($args as $arg)
		{
			$this->where($arg, 'IS NULL', '', 'AND', 'IS NULL');
		}

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function whereLike($column, $value, $join = 'AND')
	{
		$this->where($column, 'LIKE', $value, $join, 'BASIC');

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function join($table, $first, $operator = null, $second = null, $type = 'INNER')
	{
		if ($second == null)
		{
			$second = $operator;

			$operator = '=';
		}

		$this->component('join')
			->setPrimaryTable($this->table->name)
			->addJoin($table, $first, $operator, $second, $type);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function having()
	{
		$args = array_flatten(func_get_args());

		$this->component('having')
			->setHaving($args[0], $args[1], $args[2]);

		$this->bindings[] = $args[2];

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function orderBy($column, $order = 'asc')
	{
		$this->component('order')
			->setOrder($column, $order);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function groupBy($column)
	{
		$this->component('group')
			->setGroup($column);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function count($columns = '*')
	{
		return (int) array_values($this->aggregate(__FUNCTION__, (array) $columns))[0];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function aggregate($function, array $columns = array('*'))
	{
		$this->component('columns')
			->setAggregate($function)
			->setColumns($columns);

		return $this->get();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function union(Query $query)
	{
		$this->unions[] = array(
			'query' => $query,
			'all' => false
		);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function unionAll(Query $query)
	{
		$this->unions[] = array(
			'query' => $query,
			'all' => true
		);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function insert(array $records)
	{
		$bindings = array();

		foreach ($records as $record)
		{
			if (is_array($record))
			{
				$this->bindings = array_merge($bindings, array_values($record));
			}
			else
			{
				$this->bindings = array_merge($bindings, $records);
				break;
			}
		}

		$this->component('insert')
			->setTable($this->table->name)
			->addValues($records);

		return $this->runInsert();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function insertGetId(array $records)
	{
		$this->insert($records);

		return $this->connection()->lastInsertId();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function connection()
	{
		return $this->_connection;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function runSelect()
	{
		$sql = (string) $this;

		if (!empty($this->unions))
		{
			$sql = "($sql)";

			foreach ($this->unions as $union)
			{
				$this->bindings = array_merge($this->bindings, $union['query']->bindings);

				$union['query']->select($this->columns->columns);

				$sql .= ' UNION ';
				$sql .= $union['all'] ? 'ALL ': '';
				$sql .= '(' . $union['query'] . ')';
			}
		}

		return $this->connection()->read($sql, $this->bindings);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function runInsert()
	{
		return $this->connection()->create((string) $this->insert, $this->bindings);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function component($name, $force = false)
	{
		$name = strtolower($name);

		if (!is_null($this->$name) && !$force) return $this->$name;

		$class = '\\Tranquil\\Database\\Query\\Criteria_' . ucfirst($name);

		$this->$name = new $class($this->_syntax);

		return $this->$name;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __toString()
	{
		return $this->_syntax->compile($this);
	}
} // END class Query