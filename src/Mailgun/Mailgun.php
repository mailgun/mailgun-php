<?PHP

namespace Mailgun;

use Mailgun\Connection\RestClient;

use Mailgun\Messages\Messages;
use Mailgun\Messages\BatchMessage;
use Mailgun\Messages\MessageBuilder;

class Mailgun{
        
    private $apiKey;
    protected $workingDomain;
    protected $restClient;
    
    public function __construct($apiKey = null, $apiEndpoint = "api.mailgun.net"){
	    $this->restClient = new RestClient($apiKey, $apiEndpoint);
    }

	public function post($endpointUrl, $postData = array(), $files = array()){
		return $this->restClient->postRequest($endpointUrl, $postData, $files);
	}
	
	public function get($endpointUrl, $queryString = array()){
		return $this->restClient->getRequest($endpointUrl, $queryString);
	}
	
	public function delete($endpointUrl){
		return $this->restClient->getRequest($endpointUrl);
	}
	
	public function put($endpointUrl, $putData){
		return $this->restClient->putRequest($endpointUrl, $putData);
	}
    
	public function MessageBuilder(){
		return new MessageBuilder();
	}
	
	public function BatchMessage($workingDomain, $autoSend = true){
		return new BatchMessage($this->restClient, $workingDomain, $autoSend);
	}
}

?>