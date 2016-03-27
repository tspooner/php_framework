<?php namespace Tranquil\Database\Query;

/**
 * undocumented class
 *
 * @package default
 **/
class Criteria_Having extends Criteria
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $having = array();

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setHaving($column, $operator, $value)
	{
		$this->having = array(
			'column' => $column,
			'operator' => $operator,
			'value' => $value
		);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function compile()
	{
		$sql = 'HAVING ' . $this->_syntax->wrap($this->having['column']) . ' ';
		$sql .=  $this->having['operator'] . ' ' . $this->_syntax->param($this->having['value']);

		return $sql;
	}
} // END class Criteria_Where