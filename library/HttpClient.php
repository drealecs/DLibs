<?php

/**
 * Class used to provide an abstract and simplified way to interact with curl.
 * @author drealecs@gmail.com
 */
class HttpClient
{
	
	protected $_curl;
	
	protected $_headers = array();
	protected $_body = '';
	
	
	public function __construct()
	{
		$this->_init();
	}
	
	protected function _init()
	{
		$this->_curl = curl_init();
		$this->_resetCurlOptions();
	}
	
	protected function _resetCurlOptions()
	{
		curl_setopt($this->_curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->_curl, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($this->_curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->_curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:11.0) Gecko/20100101 Firefox/11.0');
		curl_setopt($this->_curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($this->_curl, CURLOPT_HEADERFUNCTION, array($this, 'processHeader'));
		curl_setopt($this->_curl, CURLOPT_WRITEFUNCTION, array($this, 'processBody'));
		
		curl_setopt($this->_curl, CURLINFO_HEADER_OUT, true);
		
	}
	
	public function setCurlOption($option, $value)
	{
		curl_setopt($this->_curl, $option, $value);
	}
	
	public function followLocation($follow = true)
	{
		curl_setopt($this->_curl, CURLOPT_FOLLOWLOCATION, $follow);
	}
	
	protected function processHeader($curl, $header)
	{
		$this->_headers[] = $header;
		return strlen($header);
	}
	
	protected function processBody($curl, $body)
	{
		$this->_body .= $body;
		return strlen($body);
	}
	
	protected function callExec()
	{
		$this->_headers = array();
		$this->_body = '';
		curl_exec($this->_curl);
		$responseCode = curl_getinfo($this->_curl, CURLINFO_HTTP_CODE);
		$effectiveUrl = curl_getinfo($this->_curl, CURLINFO_EFFECTIVE_URL);
		$sentHeaders = curl_getinfo($this->_curl, CURLINFO_HEADER_OUT);
		
		$httpCall = new HttpCall($this->_headers, $this->_body);
		
		var_dump($this->_headers, $responseCode, $effectiveUrl, $sentHeaders, preg_split('#\R#', $sentHeaders), htmlspecialchars($this->_body));
		
		return $httpCall;
		
	}
	
	
	
	
	
	protected function _buildHttpQuery($url, $getVars = array()) {
		if (empty($getVars)) {
			return $url;
		}
		$urlParts = explode('?', $url);
		$urlMainPart = $urlParts[0];
		if (isset($urlParts[1]) && !empty($urlParts[1])){
			$urlVarParts = trim($urlParts[1], '&') . '&' . http_build_query($getVars);
		}
		else {
			$urlVarParts = http_build_query($getVars);
		}
		return $urlMainPart . '?' . $urlVarParts;
	}
	
	public function get($url, $getVars = array())
	{
		$this->_resetCurlOptions();
		curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($this->_curl, CURLOPT_URL, $this->_buildHttpQuery( $url, $getVars));
		return $this->callExec();
	}
	
	public function post($url, $postVars = array(), $getVars = array())
	{
		$this->_resetCurlOptions();
		curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($this->_curl, CURLOPT_POSTFIELDS, http_build_query($postVars));
		curl_setopt($this->_curl, CURLOPT_URL, $this->_buildHttpQuery( $url, $getVars));
		return $this->callExec();
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function __destruct()
	{
		curl_close($this->_curl);
	}
	
	
}