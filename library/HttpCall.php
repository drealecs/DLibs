<?php

/**
 * 
 * @author drealecs@gmail.com
 */
class HttpCall extends HttpRequest
{
	protected $_requestHeaders = array();
	protected $_requestBody = '';
	

	public function __construct($responseHeaders, $responseBody)
	{
		$this->_responseHeaders = $responseHeaders;
		$this->_responseBody = $responseBody;
	}
	
};