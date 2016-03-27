<?php namespace Tranquil\Database\Query;

/**
 * undocumented class
 *
 * @package default
 **/
class Criteria_Join extends Criteria
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $primaryTable = null;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $joins = array();

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function addJoin($table, $first, $operator, $second, $type)
	{
		$this->joins[] = array(
			'table' => $table,
			'first' => $first,
			'operator' => $operator,
			'second' => $second,
			'type' => $type
		);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setPrimaryTable($table)
	{
		$this->primaryTable = $table;

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function compile()
	{
		$sql = null;

		foreach ($this->joins as $join)
		{
			$sql .= $join['type'] . ' JOIN ' . $this->_syntax->wrapTable($join['table']);
			$sql .= ' ON ' . $this->_syntax->wrap($join['first']);

			$sql .= ' = ';

			if (strpos($join['second'], '.') === FALSE)
			{
				$sql .= $this->_syntax->wrap($join['table'] . '.' . $join['second']);
			}
			else
			{
				$sql .= $this->_syntax->wrap($join['second']);
			}
		}

		return $sql;
	}
} // END class Criteria_Limit