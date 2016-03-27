<?php

if (!function_exists('tab_to_space'))
{
	function tab_to_space($line, $tab = 4, $nbsp = FALSE)
	{
		while (($t = mb_strpos($line,"\t")) !== FALSE)
		{
			$preTab = $t?mb_substr($line, 0, $t):'';
			$line = $preTab . str_repeat($nbsp?chr(7):' ', $tab-(mb_strlen($preTab)%$tab)) . mb_substr($line, $t+1);
		}
		return  $nbsp?str_replace($nbsp?chr(7):' ', '&nbsp;', $line):$line;
	}
}

if (!function_exists('get_real_class'))
{
	function get_real_class($obj)
	{
		$classname = get_class($obj);

		if (preg_match('@\\\\([\w]+)$@', $classname, $matches))
		{
	        $classname = $matches[1];
	    }

    	return $classname;
	}
}

if (!function_exists('Y')) {
	function Y($F)
	{
		$func = function ($f) { return $f($f); };

		return $func(function ($f) use($F)
		{
			return $F(function ($x) use($f)
			{
				$ff = $f($f);

				return $ff($x);
			});
		});
	}
}

if (!function_exists('array_flatten')) {
	function array_flatten(array $array) {
	    $return = array();
	    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
	    return $return;
	}
}

if (!function_exists('array_last')) {
	function array_last($array)
	{
		return $array[(count($array) - 1)];
	}
}

if (!function_exists('array_add')) {
	/**
	 * Add an element to an array if it doesn't exist.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return array
	 */
	function array_add($array, $key, $value)
	{
		if (!isset($array[$key])) $array[$key] = $value;

		return $array;
	}
}

if (!function_exists('array_get')) {
	/**
	 * Get an item from an array using "dot" notation.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @param  array   $defaults
	 * @return mixed
	 */
	function array_get(array $array, $key, array $defaults = array())
	{
		if (is_null($key)) return $array;

		if (isset($array[$key])) {
			if (!empty($defaults)) {
				return array_merge_recursive($defaults, $array[$key]);
			}
			return $array[$key];
		} else {
			if (!empty($defaults)) {
				return $defaults;
			}
			return false;
		}

		return false;
	}
}

function stripSlashesDeep($array)
{
	return (is_array($array) ? array_map('stripslashesDeep', $array) : stripslashes($array));
}

if (get_magic_quotes_gpc()) {
	$_POST = array_map('stripSlashesDeep', $_POST);
	$_GET = array_map('stripSlashesDeep', $_GET);
	$_COOKIE = array_map('stripSlashesDeep', $_COOKIE);
	$_REQUEST = array_map('stripSlashesDeep', $_REQUEST);
}