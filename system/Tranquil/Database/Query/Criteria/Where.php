<?php namespace Tranquil\Database\Query;

/**
 * undocumented class
 *
 * @package default
 **/
class Criteria_Where extends Criteria
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $wheres = array();

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function addWhere($column, $operator, $value, $join, $type)
	{
		$this->wheres[] = array(
			'column' => $column,
			'operator' => $operator,
			'value' => $value,
			'join' => $join,
			'type' => $type
		);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function compile()
	{
		if (empty($this->wheres)) return '';

		$sql = 'WHERE ';
		$plural = false;
		$i = 0;
		if (count($this->wheres) > 1) $plural = true;

		foreach ($this->wheres as $where)
		{
			if ($plural && $i === count($i)) $sql .= ' ' . $where['join'] . ' ';

			$sql .= $this->_syntax->wrap($where['column']) . ' ';

			switch ($where['type'])
			{
				case 'BASIC':
					$sql .= $where['operator'] . ' ' . $this->_syntax->param($where['value']);
					break;
				case 'BETWEEN':
					$sql .= 'BETWEEN ' . $where['value'][0] . ' AND ' . $where['value'][1];
					break;
				case 'IS NULL':
					$sql .= 'IS NULL';
					break;
				case 'NOT IN':
					$sql .= 'NOT ';
				case 'IN':
					$sql .= 'IN (';
					$i = 1;

					foreach ((array) $where['value'] as $value)
					{
						$sql .= $this->_syntax->param($value);
						if ($i !== count($where['value'])) $sql .= ',';
						$i++;
					}

					$sql .= ')';

					break;
			}
			$i++;
		}

		return $sql;
	}
} // END class Criteria_Where