<?php namespace Tranquil\Database\Query;

/**
 * undocumented class
 *
 * @package default
 **/
abstract class Criteria
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_syntax;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $bindings = array();

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __construct(Syntax $syntax)
	{
		$this->_syntax = $syntax;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __toString()
	{
		return $this->compile();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	abstract protected function compile();
} // END class Query_Criteria