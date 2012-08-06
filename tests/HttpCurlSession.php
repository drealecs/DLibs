<?php

class HttpCurlSession
{
	protected $id;
	protected $cookieFile;
	protected $curl;
	protected $body;
	protected $header;
	protected $useCookie;
	protected $lastPage;
	
	
	public function __construct($persistId = null, $useCookie = true)
	{
		$this->useCookie = $useCookie;
		if (isset($persistId)) {
			$this->id = $persistId;
			if ($this->useCookie) {
				$this->cookieFile = md5($persistId) . '.cookie.txt';
			}
		}
		else {
			if ($this->useCookie) {
				$this->cookieFile = md5(microtime()) . '.cookie.txt';
				usleep(1);
			}
		}
		$this->init();
	}
	
	public function setUserAgent($agent)
	{
		$this->setCurlOption(CURLOPT_USERAGENT, $agent);
	}
	
	public function setCurlOption($curlOption, $value)
	{
		curl_setopt($this->curl, $curlOption, $value);
	}
	
	protected function init()
	{
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
		if ($this->useCookie) {
			curl_setopt($this->curl, CURLOPT_COOKIEFILE, sys_get_temp_dir() . $this->cookieFile);
			curl_setopt($this->curl, CURLOPT_COOKIEJAR, sys_get_temp_dir() . $this->cookieFile);
		}
		curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:11.0) Gecko/20100101 Firefox/11.0');
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 120);
		curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, array($this, 'processHeader'));
		curl_setopt($this->curl, CURLOPT_WRITEFUNCTION, array($this, 'processBody'));
		
	}
	
	protected function processHeader($curl, $header)
	{
		$this->header[] = $header;
		return strlen($header);
	}
	
	protected function processBody($curl, $body)
	{
		$this->body .= $body;
		return strlen($body);
	}
	
	protected function callExec()
	{
		$this->body = '';
		$this->header = array();
		curl_exec($this->curl);
// 		$responseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
// 		$this->lastPage = curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL);
		curl_close($this->curl);
		return array($this->header, $this->body);
// 		return new HttpCurlResponse($this->header, $this->body, $responseCode);
	}
	
	public function get($url, $getVars = array())
	{
		$this->init();
		curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($this->curl, CURLOPT_URL, $url . '?' . http_build_query($getVars));
		return $this->callExec();
	}
	
	public function post($url, $postVars = array(), $getVars = array())
	{
		$this->init();
		curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($postVars));
		curl_setopt($this->curl, CURLOPT_URL, $url . '?' . http_build_query($getVars));
		return $this->callExec();
	}
	
	public function resetSession()
	{
		if ($this->useCookie) {
			unlink(sys_get_temp_dir() . $this->cookieFile);
		}
	}
	
	
	public function __destruct()
	{
		if (!isset($this->id)) {
			$this->resetSession();
		}
	}
	
}


class HttpCurlRequest
{
	protected $url;
	protected $getVars = array();
	protected $postVars = array();
	protected $method = 'GET';
	
	public function __construct($url = null)
	{
		if (isset($url)) {
			$this->url = $url;
		}
	}
	
	public function setMethod($method)
	{
		$this->method = $method;
		return $this;
	}
	
	public function setGetVars(array $getVars)
	{
		$this->getVars = $getVars;
	}
	
	public function setPostVars(array $postVars)
	{
		$this->postVars = $postVars;
	}
	
}

class HttpCurlResponse
{
	protected $headers = array();
	protected $body = '';
	protected $responseCode;

	public function __construct($headers, $body, $code)
	{
		$this->headers = $headers;
		$this->body = $body;
		$this->responseCode = $code;
	}
	
	public function getHeaders()
	{
		return $this->headers;
	}
	
	public function getBody()
	{
		return $this->body;
	}
	
	public function getResponseCode()
	{
		return $this->responseCode;
	}
	
	public function __toString()
	{
		return $this->body;
	}
}
