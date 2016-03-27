<?php namespace Tranquil\Configure;

use Tranquil\Core\Singleton;

/**
*
*/
class ConfigurationManager extends Singleton
{
	protected $_files = [];

	protected function __construct() {}

	public function get($path, $default = false)
	{
		$name = $path;

		$default = $this->getPath($name . '.default');

		if (file_exists($default))
			$default = $this->getFile($default);

		$path = $this->getPath($name);

		if (file_exists($path))
			$file = $this->getFile($path);

		if (!is_string($default) && !file_exists($path))
		{
			return $this->_files[$name] = $default;
		}
		else if (file_exists($path))
		{
			$this->_files[$name] = array_merge($default, $path);
		}
		else
		{
			$this->_files[$name] = array();
		}

		return $this->_files[$name];
	}

	protected function getFile($path)
	{
		return include $path;
	}

	protected function getPath($path = null)
	{
		return $path = CONFIG . $path . '.php';
	}
}