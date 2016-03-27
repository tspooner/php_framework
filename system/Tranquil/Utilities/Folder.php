<?php namespace Tranquil\Utilities;

/**
 * undocumented class
 *
 * @package default
 **/
class Folder
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $path;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_files = array();

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __construct($path = TMP, $create = false, $mode = 0755)
	{
		if ($create && !file_exists($path))
		{
			$this->create($path, $mode);
		}

		$this->cd($path);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function pwd()
	{
		return $this->path;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function cd($path)
	{
		if (is_dir($path))
		{
			return $this->path = $path;
		}

		return false;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function findByName($name)
	{
		if (file_exists($path = str_replace('//', '/', $this->path . DS . $name)))
		{
			return $path;
		}
	}
} // END class Folder