<?php namespace Tranquil\View;

/**
 * undocumented class
 *
 * @package default
 **/
class View
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_engine;

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
	protected $_path;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_data;

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __construct(Engine_Interface $engine, $view, $path, $data = array())
	{
		$this->_engine = $engine;
		$this->_name = $view;
		$this->_path = $path;
		$this->_data = (array) $data;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function render()
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getCompile()
	{
		return $this->_engine->compile($this->_path, $this->_data);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __toString()
	{
		return $this->render();
	}
} // END class View