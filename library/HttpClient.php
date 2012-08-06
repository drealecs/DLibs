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
	
	protected $_requestBody = '';
	protected $_requestHeaders = array();
	protected $_realRequestHeaders = array();
	
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
		
		curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array());
		
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
		$this->_headers[] = trim($header, "\r\n");
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
		$this->_realRequestHeaders = preg_split('#\R#', curl_getinfo($this->_curl, CURLINFO_HEADER_OUT));
		
		$httpCall = new HttpCall($this->_headers, $this->_body);
		
		var_dump($this->_requestHeaders, $this->_realRequestHeaders, $this->_requestBody, $this->_headers, $this->_body, $responseCode, $effectiveUrl);
		
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
	
	public function get($url, $getVars = array(), $headers = array())
	{
		$this->_requestBody = '';
		$this->_requestHeaders = $headers;
		
		$this->_resetCurlOptions();
		curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($this->_curl, CURLOPT_URL, $this->_buildHttpQuery( $url, $getVars));
		curl_setopt($this->_curl, CURLOPT_HTTPHEADER, $this->_requestHeaders);
		return $this->callExec();
	}
	
	public function post($url, $postVars = array(), $getVars = array(), $headers = array())
	{
		
		if (is_array($postVars)) {
			$this->_requestBody = http_build_query($postVars);
		}
		else {
			$this->_requestBody = (string)$postVars;
		}
		$this->_requestHeaders = $headers;
		
		$this->_resetCurlOptions();
		curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $this->_requestBody);
		curl_setopt($this->_curl, CURLOPT_URL, $this->_buildHttpQuery( $url, $getVars));
		curl_setopt($this->_curl, CURLOPT_HTTPHEADER, $this->_requestHeaders);
		return $this->callExec();
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function __destruct()
	{
		curl_close($this->_curl);
	}
	
	
}