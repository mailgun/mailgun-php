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
			$httpResponseCode = "200";
			$jsonResponseData = json_encode('{"message": "Queued. Thank you.","id": "<20111114174239.25659.5817@samples.mailgun.org>"}');
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/unsubscribes$/", $endpointUrl)){
			$httpResponseCode = "200";
			$jsonResponseData = json_encode('{"message": "Address has been added to the unsubscribes table","address": "ev@mailgun.net"}');
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/complaints$/", $endpointUrl)){
			$httpResponseCode = "200";
			//$jsonResponseData = json_encode('{"message": "Address has been added to the unsubscribes table","address": "ev@mailgun.net"}');
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/bounces$/", $endpointUrl)){
			$httpResponseCode = "200";
			//$jsonResponseData = json_encode('{"message": "Address has been added to the unsubscribes table","address": "ev@mailgun.net"}');
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		else{
			
		}
		return $result;	
	}
	public function getRequest($endpointUrl, $queryString = array()){
		if($endpointUrl == "domains"){
			$httpResponseCode = 200;
			$jsonResponseData = json_decode('{"total_count": 1,"items": [{"created_at": "Wed, 10 Jul 2013 19:26:52 GMT","smtp_login": "postmaster@samples.mailgun.org","name": "samples.mailgun.org","smtp_password": "4rtqo4p6rrx9"}]}');
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/unsubscribes\//", $endpointUrl)){
			$httpResponseCode = "200";
			$jsonResponseData = json_encode('{"message": "Unsubscribe event has been removed","address": "ev@mailgun.net"}');
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/unsubscribes/", $endpointUrl)){
			$httpResponseCode = "200";
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/complaints/", $endpointUrl)){
			$httpResponseCode = "200";
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/bounces/", $endpointUrl)){
			$httpResponseCode = "200";
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/stats/", $endpointUrl)){
			$httpResponseCode = "200";
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/log/", $endpointUrl)){
			$httpResponseCode = "200";
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		return $result;
	}
	public function deleteRequest($endpointUrl){
		if($endpointUrl == "domains"){
			$httpResponseCode = 200;
			$jsonResponseData = json_decode('asdfasdfasdf');
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/unsubscribes\//", $endpointUrl)){
			$httpResponseCode = "200";
			$jsonResponseData = json_encode('{"message": "Unsubscribe event has been removed","address": "ev@mailgun.net"}');
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/complaints\//", $endpointUrl)){
			$httpResponseCode = "200";
			$jsonResponseData = json_encode('{"message": "Unsubscribe event has been removed","address": "ev@mailgun.net"}');
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/bounces\//", $endpointUrl)){
			$httpResponseCode = "200";
			$jsonResponseData = json_encode('{"message": "Unsubscribe event has been removed","address": "ev@mailgun.net"}');
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		elseif(preg_match("/\/tags\//", $endpointUrl)){
			$httpResponseCode = "200";
			$jsonResponseData = json_encode('{"message": "Unsubscribe event has been removed","address": "ev@mailgun.net"}');
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		return $result;	
	}
	public function putRequest($endpointUrl, $queryString){
		if($endpointUrl == "domains"){
			$httpResponseCode = 200;
			$jsonResponseData = json_decode('asdfasdfasdf');
			if($httpResponseCode === 200){
				foreach($jsonResponseData as $key => $value){
			    	$result->$key = $value;
				}
			}
			$result->http_response_code = $httpResponseCode;
		}
		return $result;	
	}

}


?>