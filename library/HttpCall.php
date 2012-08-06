<?php

/**
 * 
 * @author drealecs@gmail.com
 */
class HttpCall
{
	protected $_requestHeaders = array();
	protected $_requestBody = '';
	
	protected $_responseHeaders = array();
	protected $_responseBody = '';
	
	protected $_responseHttpCode;
	
	
	
	public function __construct($responseHeaders, $responseBody)
	{
		$this->_responseHeaders = $responseHeaders;
		$this->_responseBody = $responseBody;
	}
	
}