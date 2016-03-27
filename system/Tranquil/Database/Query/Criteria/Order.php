<?php namespace Tranquil\Database\Query;

/**
 * undocumented class
 *
 * @package default
 **/
class Criteria_Order extends Criteria
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $column = null;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $order = null;

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setOrder($column, $order = 'asc')
	{
		$this->column = $column;
		$this->order = $order;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setOrderColumn($column)
	{
		$this->column = $column;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setOrderOrder($order)
	{
		$this->order = $order;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function compile()
	{
		$sql = "ORDER BY " . $this->_syntax->wrap($this->column) . ' ' . strtoupper($this->order);

		return $sql;
	}
} // END class Criteria_Limit