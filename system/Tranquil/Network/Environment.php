<?php namespace Tranquil\Network;

use \Tranquil\Network\Http\Headers;

/**
*
*/
class Environment implements \ArrayAccess
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_data;

	/**
	 * [__construct description]
	 */
	public function __construct()
	{
		self::$_data = Headers::getInstance()->extract($_SERVER);
		static::_url();
		static::_scheme();
		var_dump(static::$_environment);
	}

	/**
	 * [__call description]
	 * @param  [type] $name   [description]
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	function __call($name, $params)
	{
		if (strpos($name, 'get') === 0)
			return self::_get(substr($name, 3));
	}

	/**
	 * [demo description]
	 * @param  array  $params [description]
	 * @return [type]         [description]
	 */
	public function demo($params = array())
	{
		return self::$_environment = array_merge(self::$_environment, self::$_defaults, $params);
	}

	/**
	 * [_get description]
	 * @param  [type] $call [description]
	 * @return [type]       [description]
	 */
	protected static function _get($call)
	{
		if ($call == 'Scheme') return preg_replace('/:\/\/[\s\S]*$/', '', self::url());
		if ($call == 'Url') return self::url();
		if ($call == 'All') return self::$_environment;
	}

	protected static function _scheme()
	{
		if (isset(static::$_environment['Scheme'])) {
			return static::$_environment['Url'];
		} else {
			return static::$_environment['Scheme'] =
				preg_replace('/:\/\/[\s\S]*$/', '', self::_url());
		}
	}

	/**
	 * [url description]
	 * @return [type] [description]
	 */
	protected static function _url()
	{
		if (isset(static::$_environment['Url'])) {
			return static::$_environment['Url'];
		} else {
			$s = empty($_SERVER['HTTPS']) ? '' : ($_SERVER['HTTPS'] == 'on') ? 's' : '';
		    $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
		    $protocol = substr($sp, 0, strpos($sp, '/')) . $s;
		    $port = ($_SERVER['SERVER_PORT'] == '80') ? '' : (':'.$_SERVER['SERVER_PORT']);
		    return static::$_environment['Url'] =
		    	$protocol . '://' . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
		}
	}

	/**
	 * [offsetGet description]
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function offsetGet($key)
	{
		if (isset($this->_modifiers[$key])) {
			return $this->_modifiers[$key];
		}
		return isset(static::$_environment[$key]) ? static::$_environment[$key] : null;
	}

	/**
	 * [offsetSet description]
	 * @param  [type] $key [description]
	 * @param  [type] $val [description]
	 * @return [type]      [description]
	 */
	public function offsetSet($key, $val)
	{
		if (is_null($key)) {
			$this->_modifiers[] = $val;
		} else {
			$this->_modifiers[$key] = $val;
		}
	}

	/**
	 * [offsetUnset description]
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function offsetUnset($key)
	{
		return false;
		// unset(static::$_environment[$key]);
	}

	/**
	 * [offsetExists description]
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function offsetExists($key)
	{
		return isset(static::$_environment[$key]);
	}
}