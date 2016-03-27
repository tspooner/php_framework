<?php namespace Tranquil\Database\Query;

/**
 * undocumented class
 *
 * @package default
 **/
class Criteria_Table extends Criteria
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $name;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $alias;

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setAlias($alias)
	{
		$this->alias = $alias;

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function compile()
	{
		$sql = 'FROM ' . $this->_syntax->wrapTable($this->name);
		$sql .= !is_null($this->alias) ? ' AS ' . $this->_syntax->wrap($this->alias) : '';

		return $sql;
	}
} // END class Criteria_From