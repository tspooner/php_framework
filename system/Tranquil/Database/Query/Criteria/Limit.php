<?php namespace Tranquil\Database\Query;

/**
 * undocumented class
 *
 * @package default
 **/
class Criteria_Limit extends Criteria
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $limit = '18446744073709551615';

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $offset = '0';

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setLimit($limit)
	{
		$this->limit = $limit;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setOffset($offset)
	{
		$this->offset = $offset;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function compile()
	{
		$sql = "LIMIT $this->limit";
		$sql .= $this->offset ? " OFFSET $this->offset" : '';

		return $sql;
	}
} // END class Criteria_Limit