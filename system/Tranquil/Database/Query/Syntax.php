<?php namespace Tranquil\Database\Query;

use \Tranquil\Database\Query;
use \Tranquil\Database\Rule;

/**
 * undocumented class
 *
 * @package default
 **/
class Syntax
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_prefix = '';

	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	protected $_components = array(
		'columns',
		'table',
		'where',
		'order',
		'group',
		'having',
		'join',
		'limit'
	);

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __construct($prefix = '')
	{
		$this->_prefix = $prefix;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function compile(Query $query)
	{
		if (is_null($query->columns)) $query->columns = array('*');

		return trim($this->concatenate($this->compileAll($query)));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function compileAll(Query $query)
	{
		$sql = array();

		foreach ($this->_components as $component)
		{
			if (!is_null($query->$component))
			{
				$sql[$component] = (string) $query->$component;
			}
		}

		return $sql;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function concatenate($segments)
	{
		return implode(' ', array_filter($segments, function ($value)
		{
			return (string) $value !== '';
		}));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function columns(array $columns)
	{
		return implode(', ', array_map(array($this, 'wrap'), $columns));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function parameters(array $values)
	{
		return implode(', ', array_map(array($this, 'param'), $values));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function param($param)
	{
		return '?';
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function wrap($value)
	{
		$wrapped = array();

		$segments = explode('.', $value);

		foreach ($segments as $key => $seg)
		{
			if ($key == 0 && count($segments) > 1)
			{
				$wrapped[] = $this->wrapTable($seg);
			}
			else
			{
				$wrapped[] = $this->wrapValue($seg);
			}
		}

		return implode('.', $wrapped);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function wrapTable($table)
	{
		return $this->wrap($this->_prefix . $table);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function wrapValue($value)
	{
		return $value !== '*' ? sprintf($this->wrapper, $value) : $value;
	}
} // END interface Syntax_Interface