<?php

/**
 * Extents a client to be used as a browser session
 * @author drealecs@gmail.com
 */
class HttpSession extends HttpClient
{
	
	protected $_cookieFile ;
	protected $_persistentCookie = true;
	
	public function __construct($persistId = null, $persistentCookie = true)
	{
		parent::__construct();
		
		$this->_persistentCookie = $persistentCookie;
		if (!isset($persistId)) {
			$persistId = microtime();
			$this->_persistentCookie = false;
		}
		$this->_cookieFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5($persistId) . '.cookie.txt';
	}
	
	
	protected function _init()
	{
		parent::_init();
	}
	
	protected function _resetCurlOptions()
	{
		parent::_resetCurlOptions();
		
		curl_setopt($this->_curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:11.0) Gecko/20100101 Firefox/11.0');
		curl_setopt($this->_curl, CURLOPT_FOLLOWLOCATION, false);
		
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
		if (!$this->_persistentCookie) {
			$this->resetSession();
		}
	}
}