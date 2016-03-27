<?php namespace Tranquil\Utilities;

/**
 * undocumented class
 *
 * @package default
 **/
class File
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $folder;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $file = null;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $name;

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
	private $_code = array();

	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	private $_modes = array(
		'r', 'r+',
		'w', 'w+',
		'a', 'a+',
		'x', 'x+'
	);

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __construct($path, $create = false, $mode = 0755)
	{
		$this->folder = new Folder(dirname($path));

		if (!is_dir($path))
		{
			$this->name = basename($path);
		}

		$this->path = $path;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function __destruct()
	{
		$this->close();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function create()
	{
		$dir = $this->folder->pwd();

		if (is_dir($dir) && is_writable($dir) && !$this->exists())
		{
			if (touch($this->path))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function open($mode = 'r')
	{
		if (is_resource($this->file))
			return true;

		clearstatcache();

		if (!$this->exists())
		{
			if (!$this->create())
				return false;
		}

		$this->file = fopen($this->path, $mode);

		if (is_resource($this->file))
			return true;

		return false;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function close()
	{
		if (!is_resource($this->file))
			return true;

		return fclose($this->file);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function read()
	{

	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function write()
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function delete()
	{
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function exists()
	{
		return (file_exists($this->path) && is_file($this->path));
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function load()
	{
		$this->_code = file_exists($this->path) ? file($this->path) : FALSE;

		return $this->_code;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getLines($start, $limit)
	{
		return array_slice($this->load(), $start, $limit);
	}
} // END class Manager