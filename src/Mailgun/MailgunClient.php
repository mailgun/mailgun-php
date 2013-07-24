<?PHP

namespace Mailgun;

use Mailgun\Connection\HttpBroker;
use Mailgun\Unsubscribes\Unsubscribes;
use Mailgun\Messages\Messages;


use Mailgun\Connection\Exceptions\NoDomainsConfigured;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\MissingRequiredMIMEParameters;
use Mailgun\Connection\Exceptions\GenericHTTPError;

class MailgunClient{

	protected $debug;
	protected $httpBroker;
	
	public function __construct($apiKey, $domain, $debug = false){
		$this->httpBroker = new HttpBroker($apiKey, $domain, $debug);
		$this->validateCredentials();
	}
	
	public function validateCredentials(){
		$url = "domains";
		
		$response = $this->httpBroker->getRequest($url);

		$httpStatusCode = $response->http_response_code;
		
		if($httpStatusCode == 200){
			foreach ($response as $key => $value){
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
		elseif($httpStatusCode == 401){
			throw new InvalidCredentials("Your credentials are incorrect.");
		}
		else{
			throw new GenericHTTPError("A generic HTTP Error has occurred! Check your network connection and try again.");
			return false;
		}
	}
		
	//Factory Methods for Class Creation from MailgunClient
	public function Messages(){
		return new Messages($this->httpBroker);
	}
	public function Unsubscribes(){
		return new Unsubscribes($this->httpBroker);
	}
	
}

?>