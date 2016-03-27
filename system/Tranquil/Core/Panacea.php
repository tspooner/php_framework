<?php namespace Tranquil\Core; use \Closure, \ArrayAccess;

use \Tranquil\Core\Singleton;
use \Tranquil\Error\ExceptionHandler;
use \Tranquil\Configure\ConfigurationManager as Config;

/**
 * undocumented class
 *
 * @package default
 **/
class PanaceaException extends ExceptionHandler
{} // END class PanaceaException extends ExceptionHandler

/**
 * undocumented class
 *
 * @package default
 **/
class Panacea extends Singleton implements ArrayAccess
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_services = array();

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_definitionClass = '\\Tranquil\\Core\\Panacea_Definition';

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	private $_lastCreated = null;

	protected function __construct() {}

	/**
	 * undocumented function
	 *
	 * @param string|Closure $id
	 * @param Closure $service
	 * @param boolean $static
	 * @return void
	 **/
	public function define($id, $service = null, $static = false)
	{
		// If there is no service specified assume the id is the
		// concrete service path|Closure.
		if (is_null($service) || is_bool($service))
		{
			$static  = $service ? (bool) $service : false;
			$service = $id;
			$id = $this->getId($id);
		}

		if (is_array($service))
		{
			$j = 0;
			foreach ($service as $i => $s)
			{
				$this->define($i, $s);
				$j++;

				if ($j == count($service))
					$this->_lastCreated = $id;
			}

			return $this;
		}

		$this->_lastCreated = $id;
		return $this->_services[$this->getId($id)] = new Panacea_Definition($id, $service, $static);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function defined($id)
	{
		return isset($this->_services[$this->getId($id)]);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function instance($id = null)
	{
		return $id ?
			$this->get($id) :
			$this->get($this->_lastCreated);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getId($id)
	{
		$id = explode('\\', $id);

		return strtolower($id[(count($id) - 1)]);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function get($id)
	{
		$id = $this->getId($id);

		if (isset($this->_services[$id]))
		{
			return $this->_services[$id]->instance();
		}

		throw new PanaceaException(sprintf('The service "%s" must be defined.', $id));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function resolveRequest($request)
	{
		$request = explode('.', $request);
		$id = $this->getId(array_shift($request));

		if (empty($request) || $request[0] == 'class') return $this->get($id);

		$final =& $this->_dependencies[$id];

		foreach ($request as $key) {
			$final =& $final[strtolower($key)];
		}

		$value = $final;

		return $value;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	function __call($method, $parameters)
	{
		preg_match('/^get(.+)Class$/', $method, $matches);

		if (isset($matches[1]))
		{
			return $this->get($matches[1]);
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	function __set($id, $value)
	{
		$this->bind($id, $value);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	function __unset($id)
	{
		throw new BindingException('You cannot unset a service!');
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	function __get($id)
	{
		return $this->get($this->getId($id));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	function __isset($id)
	{
		return $this->bound($id);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	function offsetExists($id)
	{
		return $this->bound($id);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	function offsetGet($id)
	{
		return $this->resolveRequest($id);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	function offsetSet($id, $value)
	{
		$this->bind($id, $value);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	function offsetUnset($id)
	{
		throw new BindingException('You cannot unset a service!');
	}
} // END class Container