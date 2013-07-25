<?PHP

namespace Mailgun;

use Mailgun\Connection\HttpBroker;
use Mailgun\Unsubscribes\Unsubscribes;
use Mailgun\Messages\Messages;
use Mailgun\Complaints\Complaints;
use Mailgun\Bounces\Bounces;
use Mailgun\Stats\Stats;
use Mailgun\Logs\Logs;


use Mailgun\Connection\Exceptions\NoDomainsConfigured;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\MissingRequiredMIMEParameters;
use Mailgun\Connection\Exceptions\GenericHTTPError;

class MailgunClient{

	protected $debug;
	protected $httpBroker;
	
	public function __construct($apiKey, $domain, $debug = false){
		$this->httpBroker = new HttpBroker($apiKey, $domain, $debug);
	}
	
	//Factory Methods for Class Creation from MailgunClient
	public function Messages(){
		return new Messages($this->httpBroker);
	}
	public function Unsubscribes(){
		return new Unsubscribes($this->httpBroker);
	}
	public function Complaints(){
		return new Complaints($this->httpBroker);
	}
	public function Bounces(){
		return new Bounces($this->httpBroker);
	}
	public function Stats(){
		return new Stats($this->httpBroker);
	}
	public function Logs(){
		return new Logs($this->httpBroker);
	}	
}

?>