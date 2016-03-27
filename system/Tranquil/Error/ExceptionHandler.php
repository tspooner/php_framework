<?php namespace Tranquil\Error;

use \Tranquil\Utilities\File;

set_exception_handler(array('Tranquil\Error\ExceptionHandler', 'handleException'));

/**
*
*/
class ExceptionHandler extends \Exception
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_custom;

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected static $_displayFile = 'Templates/error.php';

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected static $_cssFile = 'Templates/styles.css';

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $_calledException;

	public function __construct($message, $code = 500)
	{
		$this->calledException = str_replace('\\', ' \\ ', preg_replace('/([a-zA-Z]+)$/', '<span class="brown">$1</span>', get_called_class()));
		parent::__construct($message, $code);
	}

	public static function handleException(\Exception $e)
	{
		include static::$_displayFile;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getFileLines($path, $start, $limit)
	{
		$file = new File($path);

		return $file->getLines($start, $limit);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getCustom()
	{
		return $this->_custom;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function setCustom($string)
	{
		$this->_custom = (string) $string;

		return $this;
	}
}