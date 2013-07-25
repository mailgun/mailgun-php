<?php
namespace Mailgun\Tests\Connection;

use Mailgun\Connection\HttpBroker;

class TestBroker extends HttpBroker{
	private $apiKey;
	protected $domain;
	protected $debug;
	
	protected $apiEndpoint = API_ENDPOINT;
	protected $apiVersion = API_VERSION;
	protected $apiUser = API_USER;
	protected $sdkVersion = SDK_VERSION;
	protected $sdkUserAgent = SDK_USER_AGENT;

	public function __construct($apiKey, $domain, $debug = false){
		$this->apiKey = $apiKey;
		$this->domain = $domain;
		$this->debug = $debug;
	}
	
	public function postRequest($endpointUrl, $postData = array(), $files = array()){
		if(preg_match("/\/messages$/", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/unsubscribes$/", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/complaints$/", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/bounces$/", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		else{
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		return $result;	
	}
	public function getRequest($endpointUrl, $queryString = array()){
		if($endpointUrl == "domains"){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/unsubscribes\//", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/unsubscribes/", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/complaints/", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/bounces/", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/stats/", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/log/", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		else{
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		return $result;
	}
	public function deleteRequest($endpointUrl){
		if($endpointUrl == "domains"){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/unsubscribes\//", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/complaints\//", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/bounces\//", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		elseif(preg_match("/\/tags\//", $endpointUrl)){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		else{
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		return $result;	
	}
	public function putRequest($endpointUrl, $queryString){
		if($endpointUrl == "domains"){
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		else{
			return $this->responseHandler($endpointUrl, $httpResponseCode = 200);
		}
		return $result;	
	}
	public function responseHandler($endpointUrl, $httpResponseCode = 200){
		if($httpResponseCode === 200){
			$result = new \stdClass();
			$jsonResponseData = json_decode('{"message": "Some JSON Response Data"}');
			foreach($jsonResponseData as $key => $value){
			    $result->http_response_body->$key = $value;
			}
		}
		elseif($httpStatusCode == 400){
			throw new MissingRequiredMIMEParameters(EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
		}
		elseif($httpStatusCode == 401){
			throw new InvalidCredentials(EXCEPTION_INVALID_CREDENTIALS);
		}
		elseif($httpStatusCode == 401){
			throw new GenericHTTPError(EXCEPTION_INVALID_CREDENTIALS);
		}
		elseif($httpStatusCode == 404){
			throw new MissingEndpoint(EXCEPTION_MISSING_ENDPOINT);
		}
		else{
			throw new GenericHTTPError(EXCEPTION_GENERIC_HTTP_ERROR);
			return false;
		}
		$result->http_response_code = $httpResponseCode;
		return $result;
	}


}


?>