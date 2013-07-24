<?PHP

namespace Mailgun\Connection;
	
require dirname(__DIR__) . '/globals.php';

use Guzzle\Http\Client as Guzzle;

use Mailgun\Connection\Exceptions\NoDomainsConfigured;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\MissingRequiredMIMEParameters;
use Mailgun\Connection\Exceptions\GenericHTTPError;

class Client{

	protected $apiKey;
	protected $domain;
	protected $client;
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
		if($this->debug){
			$this->client = new Guzzle('https://api.ninomail.com/' . $this->apiVersion . '/', array('ssl.certificate_authority' => false));
			$this->client->setDefaultOption('auth', array ($this->apiUser, $this->apiKey));	
			$this->client->setDefaultOption('exceptions', false);
			$this->client->setUserAgent($this->sdkUserAgent . '/' . $this->sdkVersion);
			$this->validateCredentials();
		}
		else{
			$this->client = new Guzzle('https://' . $this->apiEndpoint . '/' . $this->apiVersion . '/');
			$this->client->setDefaultOption('auth', array ($this->apiUser, $this->apiKey));	
			$this->client->setDefaultOption('exceptions', false);
			$this->client->setUserAgent($this->sdkUserAgent . '/' . $this->sdkVersion);
			$this->validateCredentials();
		}
	}
	
	public function validateCredentials(){
		$url = "domains";
		$data = null;
		$request = $this->client->get($url, array(), $data);
	
		$response = $request->send();
		
		if($response->getStatusCode() == 200){
			$jsonResp = $response->json();
			foreach ($jsonResp as $key => $value){
			    $object->$key = $value;
			}
			if($object->total_count > 0){
				return true;
			}
			else{
				throw new NoDomainsConfigured("You don't have any domains on your account.");
				return false;
			}
		}
		elseif($response->getStatusCode() == 401){
			throw new InvalidCredentials("Your credentials are incorrect.");
		}
		else{
			throw new GenericHTTPError("A generic HTTP Error has occurred! Check your network connection and try again.");
			return false;
		}
	}
	
	public function sendMessage($message){
		if(array_key_exists("from", $message) && 
		   array_key_exists("to", $message) && 
		   array_key_exists("subject", $message) &&
		   (array_key_exists("text", $message) || array_key_exists("html", $message))){
				$domain = $this->domain;
				if($this->debug){
					$request = $this->client->post("$domain/messages", array(), $message);
					if(isset($message["attachment"])){
						foreach($message["attachment"] as $attachments){
							$request->addPostFile("attachment", $attachments);
						}
						unset($message["attachment"]);
					}
					if(isset($message["inline"])){
						foreach($message["inline"] as $inlineAttachments){
							$request->addPostFile("inline", $inlineAttachments);
						}
					}
					$response = $request->send();
				}
			else{
				$request = $this->client->post("$domain/messages", array(), $message);
				if(isset($message["attachment"])){
					foreach($message["attachment"] as $attachments){
						$request->addPostFile("attachment", $attachments);
					}
				unset($message["attachment"]);
				}
				if(isset($message["inline"])){
					foreach($message["inline"] as $inlineAttachments){
						$request->addPostFile("inline", $inlineAttachments);
					}
				}
				$response = $request->send();
			}
		return $response;
	}
	throw new MissingRequiredMIMEParameters("You are missing the minimum parameters to send a message.");
	}
	public function postUnsubscribe($data){
		$domain = $this->domain;
		$request = $this->client->post("$domain/unsubscribes", array(), $data);
		return $request->send();
	}	
	public function deleteUnsubscribe($address){
		$domain = $this->domain;
		$request = $client->delete("$domain/unsubscribes/$address");
		return $request->send();
	}
}

?>