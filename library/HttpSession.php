<?php

/**
 * Extents a client to be used as a browser session
 * @author drealecs@gmail.com
 */
class HttpSession extends HttpClient
{
	
	protected $_cookieFile ;
	
	public function __construct($persistId = null)
	{
		parent::__construct();
		
		if (isset($persistId)) {
			$this->_cookieFile = sys_get_temp_dir() . md5($persistId) . '.cookie.txt';
		}
		else {
			$this->_cookieFile = sys_get_temp_dir() . md5(microtime()) . '.cookie.txt';
		}
	}
	
	
	protected function _init()
	{
		parent::_init();
	}
	
	protected function _resetCurlOptions()
	{
		parent::_resetCurlOptions();
		curl_setopt($this->_curl, CURLOPT_COOKIEFILE, $this->_cookieFile);
		curl_setopt($this->_curl, CURLOPT_COOKIEJAR, $this->_cookieFile);
	}
	
	
	
	
	
	
	
	
	
	
	public function resetSession()
	{
		unlink($this->_cookieFile);
	}
	
	
	public function __destruct()
	{
		parent::__destruct();
		$this->resetSession();
	}
}