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
use Mailgun\Connection\HttpBroker;
use Mailgun\Unsubscribes\Unsubscribes;

class MailgunClient{
    
    /* 
     * Instantiate the HttpBroker to make it available to all 
     * classes created from here.
    */
    
    public function __construct($apiKey, $workingDomain, $debugMode = false){
        $this->httpBroker = new HttpBroker($apiKey, $workingDomain, $debugMode);
    }
    
    /* 
     * Factory methods for instantiating each endpoint class.
     * If a new endpoint is added, create a factory method here.
     */    
     
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
    
    public function Routes(){
        return new Routes($this->httpBroker);
    }
    
    public function Campaigns(){
        return new Campaigns($this->httpBroker);
    }
    
    public function Lists(){
        return new Lists($this->httpBroker);
    }
    public function Address(){
        return new Address($this->httpBroker);
    }
}

?>