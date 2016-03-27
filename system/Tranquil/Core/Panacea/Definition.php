<?php namespace Tranquil\Core; use \Closure;

use \Tranquil\Configure\ConfigurationManager as Config;

/**
 * undocumented class
 *
 * @package default
 **/
class Panacea_Definition
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_id = null;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_class = null;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_constructor = null;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_static = false;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_arguments = array();

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_defaultArguments = array();

	/**s
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_calls = array();

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_callback = null;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_init = false;

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __construct($id, $service, $static = false)
	{
		$this->setId($id);
		$this->setClass($service);
		$this->setStatic($static);

		$this->resetArguments();

		$this->setConstructor(
			$this->makeConstructor()
		);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function instance()
	{
		if ($this->isInit() && $this->_static)
			return $this->_class;

		$constructor = $this->_constructor;

		$this->_class = $constructor(
			$this->getArguments()
		);

		$this->_init = true;

		return $this->_class;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function isInit()
	{
		return $this->_init;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function makeConstructor()
	{
		$class = $this->_class;

		$constructor = function($c) use ($class)
		{
			return new $class($c);
		};

		return $constructor;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setId($id)
	{
		$this->_id = $id;

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setConstructor($method)
	{
		$this->_constructor = $method;

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getConstructor()
	{
		return $this->_constructor;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setClass($class)
	{
		$this->_class = $class;

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getClass()
	{
		return $this->_class;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function resetArguments()
	{
		return $this->_arguments = Config::getInstance()->get($this->_id);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function addArgument($argument)
	{
		$this->_arguments[] = $argument;

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getArguments()
	{
		return array_merge($this->_defaultArguments, $this->_arguments);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setMethodCalls(array $calls = array())
	{
		$this->_calls = array();
		foreach ($calls as $call) {
			$this->assMethodCall($call[0], $call[1]);
		}

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function addMethodCall($method, array $arguments = array())
	{
		$this->_calls[] = array($method, $arguments);

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getMethodCalls()
	{
		return $this->_calls;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setStatic($static)
	{
		$this->_static = (boolean) $static;

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function isStatic()
	{
		return $this->_static;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setCallback($callback)
	{
		$this->_callback = $callback;

		return $this;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getCallback()
	{
		return $this->_callback;
	}
} // END class Panacea_Definition