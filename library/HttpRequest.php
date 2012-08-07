<?php

/**
 * 
 * @author drealecs@gmail.com
 */
class HttpRequest
{
	
	const GET     = 'GET';
	const POST    = 'POST';

	// the rest of the methods to be implemented later...
	const PUT     = 'PUT';
	const HEAD    = 'HEAD';
	const DELETE  = 'DELETE';
	const TRACE   = 'TRACE';
	const OPTIONS = 'OPTIONS';
	const CONNECT = 'CONNECT';
	const MERGE   = 'MERGE';
	
	
	protected $_method;
	protected $_requestUrl;
	protected $_requestHeaders = array();
	protected $_requestGet = array();
	protected $_requestPost = array();

	
	public function __construct($method = self::GET)
	{
		switch ($method) {
			case self::GET:
			case self::POST:
				$this->_method = $method;
				break;
			default:
				throw new Exception("Method {$method} not implemented.");
		}
	}
	
	public function getMethod()
	{
		return $this->_method;
	}
	
	public function getRequestUrl()
	{
		return $this->_requestUrl;
	}
	
	public function setRequestUrl($url)
	{
		$this->_requestUrl = $url;
	}
	
	public function getRequestHeaders()
	{
		return $this->_requestHeaders;
	}
	
	public function setRequestHeaders($headers)
	{
		$this->_requestHeaders = $headers;
	}
	
	public function getRequestGet()
	{
		return $this->_requestGet;
	}
	
	public function setRequestGet($get = array())
	{
		$this->_requestGet = $get;
	}
	
	public function getRequestPost()
	{
		return $this->_requestPost;
	}
	
	public function setRequestPost($post = array())
	{
		$this->_requestPost = $post;
	}
	
	
	
	
	
	
	
}