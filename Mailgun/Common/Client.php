<?
namespace Mailgun\Common;
	
require_once 'globals.php';

use Guzzle\Http\Client as Guzzler;
use Mailgun\Exceptions\NoDomainsConfigured;
use Mailgun\Exceptions\HTTPError;

class Client{

	protected $apiKey;
	protected $domain;
	protected $client;
	
	protected $apiEndpoint = API_ENDPOINT;
	protected $apiVersion = API_VERSION;
	protected $apiUser = API_USER;
	protected $sdkVersion = SDK_VERSION;
	protected $sdkUserAgent = SDK_USER_AGENT;

	public function __construct($apiKey, $domain){
		$this->apiKey = $apiKey;
		$this->domain = $domain;
		$this->client = new Guzzler('https://' . $this->apiEndpoint . '/' . $this->apiVersion . '/');
		$this->client->setDefaultOption('auth', array ($this->apiUser, $this->apiKey));	
		$this->client->setUserAgent($this->sdkUserAgent . '/' . $this->sdkVersion);
	}
	
	public function validateCredentials(){
		$url = "domains";
	
		$request = $this->client->get($url, array(), $data);
	
		$response = $request->send();
		
		if($response->getStatusCode() == 200){
			$jsonResp = $response ->json();
			foreach ($jsonResp as $key => $value){
			    $object->$key = $value;
			}
			if($object->total_count > 0){
				return true;
			}
			else{
				throw new NoDomainsConfigured("You don't have any domains on your account!");
				return false;
			}
		}
		else{
			throw new HTTPError("An HTTP Error has occurred! Try again.");
			return false;
		}
	}
	
	public function getRequest($options, $data){
		
		$url = $options['url'];
	
		$request = $this->client->get($url, array(), $data);
		
		$response = $request->send();
		
		return $response->getBody();
	}
	public function postRequest($options, $data){
		
		$url = $options['url'];	
		
		$request = $this->client->post($url, array(), $data);
		
		$response = $request->send();
		
		return $response->getBody();
	}
	public function putRequest($options, $data){
		$url = $options['url'];
	
		$request = $this->client->put($url, array(), $data);
		
		$response = $request->send();
		
		return $response->getBody();
	}
	public function deleteRequest($options, $data){
		$url = $options['url'];
	
		$request = $this->client->delete($url);
		
		$response = $request->send();
		
		return $response->getBody();
	}
}

?>