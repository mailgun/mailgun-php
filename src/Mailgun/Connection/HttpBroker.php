<?PHP

namespace Mailgun\Connection;
	
require dirname(__DIR__) . '/globals.php';

use Guzzle\Http\Client as Guzzle;
use Mailgun\MailgunClient;

use Mailgun\Connection\Exceptions\NoDomainsConfigured;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\MissingRequiredMIMEParameters;
use Mailgun\Connection\Exceptions\GenericHTTPError;

class HttpBroker{

	private $apiKey;
	protected $workingDomain;
	protected $debug;
	
	protected $apiEndpoint = API_ENDPOINT;
	protected $apiVersion = API_VERSION;
	protected $apiUser = API_USER;
	protected $sdkVersion = SDK_VERSION;
	protected $sdkUserAgent = SDK_USER_AGENT;

	public function __construct($apiKey, $domain, $debug = false){
		$this->apiKey = $apiKey;
		$this->workingDomain = $domain;
		$this->debug = $debug;
	}
	
	public function postRequest($endpointUrl, $postData = array(), $files = array()){
		if($this->debug){
			$this->client = new Guzzle('https://api.ninomail.com/' . $this->apiVersion . '/', array('ssl.certificate_authority' => false));
		}
		else{
			$this->client = new Guzzle('https://' . $this->apiEndpoint . '/' . $this->apiVersion . '/');
		}

		$this->client->setDefaultOption('auth', array ($this->apiUser, $this->apiKey));	
		$this->client->setDefaultOption('exceptions', true);
		$this->client->setUserAgent($this->sdkUserAgent . '/' . $this->sdkVersion);
		
		$request = $this->client->post($endpointUrl, array(), $postData);
		
		if(isset($files["attachment"])){
			foreach($files["attachment"] as $attachment){
				$request->addPostFile("attachment", $attachment);
			}
		}
		if(isset($files["inline"])){
			foreach($files["inline"] as $attachment){
				$request->addPostFile("inline", $attachment);
			}
		}		
		$response = $request->send();
		$httpResponeCode = $response->getStatusCode();
		if($httpResponeCode === 200){
			$jsonResponseData = $response->json();
			foreach ($jsonResponseData as $key => $value){
			    $result->$key = $value;
			}
		}
		elseif($httpStatusCode == 401){
			throw new InvalidCredentials("Your credentials are incorrect.");
		}
		else{
			throw new GenericHTTPError("A generic HTTP Error has occurred! Check your network connection and try again.");
			return false;
		}
		$result->http_response_code = $httpResponeCode;
		return $result;
	}
	
	public function getRequest($endpointUrl, $queryString = array()){
		if($this->debug){
			$this->client = new Guzzle('https://api.ninomail.com/' . $this->apiVersion . '/', array('ssl.certificate_authority' => false));
		}
		else{
			$this->client = new Guzzle('https://' . $this->apiEndpoint . '/' . $this->apiVersion . '/');
		}
		
		$this->client->setDefaultOption('auth', array ($this->apiUser, $this->apiKey));	
		$this->client->setDefaultOption('exceptions', true);
		$this->client->setUserAgent($this->sdkUserAgent . '/' . $this->sdkVersion);

		$request = $this->client->get($endpointUrl, $queryString);
		$response = $request->send();
		$httpResponeCode = $response->getStatusCode();
		if($httpResponeCode === 200){
			$jsonResponseData = $response->json();
			foreach ($jsonResponseData as $key => $value){
			    $result->$key = $value;
			}
		}
		elseif($httpStatusCode == 401){
			throw new InvalidCredentials("Your credentials are incorrect.");
		}
		else{
			throw new GenericHTTPError("A generic HTTP Error has occurred! Check your network connection and try again.");
			return false;
		}
		$result->http_response_code = $httpResponeCode;
		return $result;
	}
	
	public function deleteRequest($endpointUrl){
		if($this->debug){
			$this->client = new Guzzle('https://api.ninomail.com/' . $this->apiVersion . '/', array('ssl.certificate_authority' => false));
		}
		else{
			$this->client = new Guzzle('https://' . $this->apiEndpoint . '/' . $this->apiVersion . '/');
		}
		
		$this->client->setDefaultOption('auth', array ($this->apiUser, $this->apiKey));	
		$this->client->setDefaultOption('exceptions', true);
		$this->client->setUserAgent($this->sdkUserAgent . '/' . $this->sdkVersion);
		
		$request = $this->client->delete($endpointUrl);
		$response = $request->send();
		$httpResponeCode = $response->getStatusCode();
		if($httpResponeCode === 200){
			$jsonResponseData = $response->json();
			foreach ($jsonResponseData as $key => $value){
			    $result->$key = $value;
			}
		}
		elseif($httpStatusCode == 401){
			throw new InvalidCredentials("Your credentials are incorrect.");
		}
		else{
			throw new GenericHTTPError("A generic HTTP Error has occurred! Check your network connection and try again.");
			return false;
		}
		$result->http_response_code = $httpResponeCode;
		return $result;		
	}
	
	public function putRequest($endpointUrl, $queryString){
		if($this->debug){
			$this->client = new Guzzle('https://api.ninomail.com/' . $this->apiVersion . '/', array('ssl.certificate_authority' => false));
		}
		else{
			$this->client = new Guzzle('https://' . $this->apiEndpoint . '/' . $this->apiVersion . '/');
		}
		
		$this->client->setDefaultOption('auth', array ($this->apiUser, $this->apiKey));	
		$this->client->setDefaultOption('exceptions', true);
		$this->client->setUserAgent($this->sdkUserAgent . '/' . $this->sdkVersion);
		$request = $this->client->put($endpointUrl, $queryString);
		$response = $request->send();
		$httpResponeCode = $response->getStatusCode();
		if($httpResponeCode === 200){
			$jsonResponseData = $response->json();
			foreach ($jsonResponseData as $key => $value){
			    $result->$key = $value;
			}
		}
		elseif($httpStatusCode == 401){
			throw new InvalidCredentials("Your credentials are incorrect.");
		}
		else{
			throw new GenericHTTPError("A generic HTTP Error has occurred! Check your network connection and try again.");
			return false;
		}
		$result->http_response_code = $httpResponeCode;
		return $result;
	}
	public function returnWorkingDomain(){
		return $this->workingDomain;
	}
}

?>