<?php namespace Tranquil\Benchmark;

use Tranquil\Core\Singleton;

/**
*
*/
class Timer extends Singleton
{
	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	public $main = [];

	/**
	 * undocumented class variable
	 *
	 * @var array
	 **/
	public $markers = [];

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	protected function __construct()
	{
		$this->mark();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function mark($name = null)
	{
		if ($name) {
			$this->markers[$name][0]['time'] = microtime(true);
			$this->markers[$name][0]['memory'] = memory_get_usage(false);
		} else {
			$this->main[0]['time'] = microtime(true);
			$this->main[0]['memory'] = memory_get_usage(false);
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function checkpoint($name = null, $out = false)
	{
		if ($name) {
			$i = count($this->markers[$name]) + 1;
			$this->markers[$name][$i]['time'] = (microtime(true) - $this->markers[$name][0]['time']);
			$this->markers[$name][$i]['memory'] = memory_get_usage(false);
		} else {
			$i = count($this->main) + 1;
			$this->main[$i]['time'] = (microtime(true) - $this->main[0]['time']);
			$this->main[$i]['memory'] = memory_get_usage(false);
		}

		return $name ?
			end($this->markers[$name]) :
			end($this->main);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 **/
	public function end($name = null)
	{
		$output = "<br /><br />\n<pre>";
		$this->checkpoint();

		if ($name) {
			foreach ($this->markers[$name] as $key => $val)
			{
				$output .= "Time: {$val['time']} | Memory: {$val['memory']}<br />\n";
			}
		} else {
			foreach ($this->main as $key => $val)
			{
				$output .= "Time: {$val['time']} | Memory: {$val['memory']}<br />\n";
			}
		}
		$output .= "<br />\nPeak memory usage: " . memory_get_peak_usage();
		$output .= "</pre>";
		return $output;
	}
}