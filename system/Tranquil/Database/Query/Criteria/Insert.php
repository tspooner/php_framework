<?php namespace Tranquil\Database\Query;

/**
 * undocumented class
 *
 * @package default
 **/
class Criteria_Insert extends Criteria
{
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
	protected $_records = array();

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function addValues(array $records)
	{
		$this->_records = array_merge($this->_records, $records);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setTable($name)
	{
		$this->_name = $name;

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function compile()
	{
		$table = $this->_syntax->wrapTable($this->_name);

		$columns = $this->_syntax->columns(array_keys($this->_records));
		$parameters = $this->_syntax->parameters($this->_records);

		return "INSERT INTO $table ($columns) VALUES ($parameters)";
	}
} // END class Insert