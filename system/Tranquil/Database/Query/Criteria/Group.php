<?php namespace Tranquil\Database\Query;

/**
 * undocumented class
 *
 * @package default
 **/
class Criteria_Group extends Criteria
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $column = null;

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setGroup($column)
	{
		$this->column = $column;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function compile()
	{
		$sql = "GROUP BY " . $this->_syntax->wrap($this->column);

		return $sql;
	}
} // END class Criteria_Limit