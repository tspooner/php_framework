<?php namespace Tranquil\Network;

use \Tranquil\Core\Panacea;
use \Tranquil\Network\Http\Headers;
use \Tranquil\Network\Http\QueryField as Parse;
use \Tranquil\Utilities\Collection;

/**
*
*/
class Request
{
    /**
     * undocumented class variable
     *
     * @var string
     **/
    public $headers;

    /**
     * undocumented class variable
     *
     * @var string
     **/
    public $cookies;

    /**
     * undocumented class variable
     *
     * @var string
     **/
    protected $_request = array(
        'controller' => null,
        'action' => null
    );

    /**
     * undocumented class variable
     *
     * @var array
     **/
    protected $_detectors = array(
		'get' => array('key' => 'REQUEST_METHOD', 'val' => 'GET'),
		'post' => array('key' => 'REQUEST_METHOD', 'val' => 'POST'),
		'put' => array('key' => 'REQUEST_METHOD', 'val' => 'PUT'),
		'delete' => array('key' => 'REQUEST_METHOD', 'val' => 'DELETE'),
		'head' => array('key' => 'REQUEST_METHOD', 'val' => 'HEAD'),
		'options' => array('key' => 'REQUEST_METHOD', 'val' => 'OPTIONS'),
		'ssl' => array('key' => 'HTTPS', 'val' => 1),
		'ajax' => array('key' => 'HTTP_X_REQUESTED_WITH', 'val' => 'XMLHttpRequest'),
	);

    /**
     * undocumented class variable
     *
     * @var array
     **/
    protected static $_formTypes = array(
        'application/x-www-form-urlencoded',
        'multipart/form-data'
    );

	public function __construct()
	{
        $this->headers = new Headers(Headers::extract());
        $this->cookies = new Collection($_COOKIE);
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function getMethod()
    {
        return $this->headers->REQUEST_METHOD;
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function is($name)
    {
        $me = $this->_detectors[strtolower($name)];

        return $this->headers->$me['key'] == $me['val'];
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function params($key = false)
    {
        $params = array_merge($this->get(), $this->post());
        if ($key) return isset($params[$key]) ? $params[$key] : null;
        return $params;
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function get()
    {
        
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function __call($name, $args)
    {
        if (strpos($name, 'is') === 0)
            return $this->is(str_replace('is', '', $name));
    }
}