<?PHP

namespace Mailgun;

use Mailgun\Logs\Logs;
use Mailgun\Stats\Stats;
use Mailgun\Lists\Lists;
use Mailgun\Routes\Routes;
use Mailgun\Bounces\Bounces;
use Mailgun\Address\Address;
use Mailgun\Messages\Messages;
use Mailgun\Campaigns\Campaigns;
use Mailgun\Complaints\Complaints;
use Mailgun\Connection\RestClient;
use Mailgun\Unsubscribes\Unsubscribes;

class Mailgun{
    
    /* 
     * Instantiate the RestClient to make it available to all 
     * classes created from here.
    */
    
    private $apiKey;
    protected $workingDomain;
    protected $restClient;
    protected $debugMode;
    
    public function __construct($apiKey = null, $workingDomain = null, $debugMode = false){
    	if(isset($apiKey) && isset($workingDomain)){
	    	  $this->restClient = new RestClient($apiKey, $workingDomain, $debugMode);
    	}
    	else{
	    	$this->apiKey = $apiKey;
	    	$this->workingDomain = $workingDomain;
    	}
    }
    
    private function InstantiateNewClient($workingDomain, $apiKey){
		if(isset($workingDomain) || isset($apiKey)){
	    	if(isset($workingDomain)){
    			$this->workingDomain = $workingDomain;
	    	}
	    	if(isset($apiKey)){
	    		$this->apiKey = $apiKey;
	    	}
			return true;
    	}
    	if(isset($this->restClient)){
	    	return false;
    	}
		else{
			throw new Exception("A valid set of credentials is required to work with this endpoint.");
		}
    }
    
    /* 
     * Factory methods for instantiating each endpoint class.
     * If a new endpoint is added, create a factory method here.
     * Each endpoint can accept a new domain and API key if you want to 
     * switch accounts or domains after instantiating the MailgunClient.
     * This is for future support of RBAC.
     */    
     
    public function Messages($workingDomain = null, $apiKey = null){
    	if($this->InstantiateNewClient($workingDomain, $apiKey)){
    		$newClient = new RestClient($this->apiKey, $this->workingDomain, $this->debugMode);
			return new Messages($newClient);
    	}	
    	else{
	    	return new Messages($this->restClient);	
    	}
    }
    
    public function Unsubscribes($workingDomain = null, $apiKey = null){
    	if($this->InstantiateNewClient($workingDomain, $apiKey)){
    		$newClient = new RestClient($this->apiKey, $this->workingDomain, $this->debugMode);
			return new Unsubscribes($newClient);
    	}	
    	else{
	    	return new Unsubscribes($this->restClient);	
    	}
    }
    
    public function Complaints($workingDomain = null, $apiKey = null){
    	if($this->InstantiateNewClient($workingDomain, $apiKey)){
    		$newClient = new RestClient($this->apiKey, $this->workingDomain, $this->debugMode);
			return new Complaints($newClient);
    	}	
    	else{
	    	return new Complaints($this->restClient);	
    	}
    }
    
    public function Bounces($workingDomain = null, $apiKey = null){
    	if($this->InstantiateNewClient($workingDomain, $apiKey)){
    		$newClient = new RestClient($this->apiKey, $this->workingDomain, $this->debugMode);
			return new Bounces($newClient);
    	}	
    	else{
	    	return new Bounces($this->restClient);	
    	}
    }
    
    public function Stats($workingDomain = null, $apiKey = null){
    	if($this->InstantiateNewClient($workingDomain, $apiKey)){
    		$newClient = new RestClient($this->apiKey, $this->workingDomain, $this->debugMode);
			return new Stats($newClient);
    	}	
    	else{
	    	return new Stats($this->restClient);	
    	}
    }
    
    public function Logs($workingDomain = null, $apiKey = null){
    	if($this->InstantiateNewClient($workingDomain, $apiKey)){
    		$newClient = new RestClient($this->apiKey, $this->workingDomain, $this->debugMode);
			return new Logs($newClient);
    	}	
    	else{
	    	return new Logs($this->restClient);	
    	}
    }
    
    public function Routes($workingDomain = null, $apiKey = null){
    	if($this->InstantiateNewClient($workingDomain, $apiKey)){
    		$newClient = new RestClient($this->apiKey, $this->workingDomain, $this->debugMode);
			return new Routes($newClient);
    	}	
    	else{
	    	return new Routes($this->restClient);	
    	}
    }
    
    public function Campaigns($workingDomain = null, $apiKey = null){
    	if($this->InstantiateNewClient($workingDomain, $apiKey)){
    		$newClient = new RestClient($this->apiKey, $this->workingDomain, $this->debugMode);
			return new Campaigns($newClient);
    	}	
    	else{
	    	return new Campaigns($this->restClient);	
    	}
    }
    
    public function Lists($workingDomain = null, $apiKey = null){
    	if($this->InstantiateNewClient($workingDomain, $apiKey)){
    		$newClient = new RestClient($this->apiKey, $this->workingDomain, $this->debugMode);
			return new Lists($newClient);
    	}	
    	else{
	    	return new Lists($this->restClient);	
    	}
    }
    public function Address($workingDomain = null, $apiKey = null){
    	if($this->InstantiateNewClient($workingDomain, $apiKey)){
    		$newClient = new RestClient($this->apiKey, $this->workingDomain, $this->debugMode);
			return new Address($newClient);
    	}	
    	else{
	    	return new Address($this->restClient);	
    	}
    }
}

?>