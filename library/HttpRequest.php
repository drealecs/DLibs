<?php

/**
 * 
 * @author drealecs@gmail.com
 */
class HttpRequest
{
	protected $_methodType = '';
	protected $_requestHeaders = array();
	protected $_requestBody = '';
	protected $_requestUrl = '';
	
	
	public function __construct($requestUrl, $requestHeaders, $requestBody)
	{
		$this->_responseHeaders = $requestHeaders;
		$this->_responseBody = $requestBody;
	}
	
}