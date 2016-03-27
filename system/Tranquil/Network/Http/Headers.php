<?php namespace Tranquil\Network\Http;

use \Tranquil\Utilities\Collection;

/**
*
*/
class Headers extends Collection
{
	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public static function extract()
	{
		return $_SERVER;
	}
}