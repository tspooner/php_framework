<?php namespace Tranquil\Utilities;

/**
 * undocumented class
 *
 * @package default
 **/
class Collection
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_resources = array();

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __construct(array $resources)
	{
		$this->_resources = $resources;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __get($name)
	{
		if (isset($this->$name))
			return $this->$name;

		if (isset($this->_resources[$name]))
			return $this->_resources[$name];
	}
} // END class Collection