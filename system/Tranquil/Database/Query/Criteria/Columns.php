<?php namespace Tranquil\Database\Query;

/**
 * undocumented class
 *
 * @package default
 **/
class Criteria_Columns extends Criteria
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $method = null;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $distinct = false;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $columns = array('*');

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setAggregate($method)
	{
		$this->method = $method;

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setColumns($columns)
	{
		$this->columns = $columns;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function addColumns($columns)
	{
		if (is_null($this->columns)) $this->setColumns($columns);

		$this->columns = array_unique(array_merge($this->columns, $columns));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setDistinct($boolean)
	{
		$this->distinct = (boolean) $boolean;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function compile()
	{
		$columns = implode(', ', array_map(array($this->_syntax, 'wrap'), array_unique($this->columns)));

		$select = $this->distinct ? 'SELECT DISTINCT ' : 'SELECT ';

		if (is_null($this->method)) return $select . $columns;

		switch ($this->method) {
			case 'count':
				return $select . 'COUNT(' . $columns . ') as [COUNT]';
				break;
		}
	}
} // END class Criteria_Columns extends Criteria