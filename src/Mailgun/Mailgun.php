<?PHP

namespace Mailgun;

require 'Constants/Constants.php';

use Mailgun\Messages\Messages;
use Mailgun\Connection\Exceptions;
use Mailgun\Connection\RestClient;
use Mailgun\Messages\BatchMessage;
use Mailgun\Lists\OptInHandler;
use Mailgun\Messages\MessageBuilder;

/* 
   This class is the base class for the Mailgun SDK. 
   See the official documentation for usage instructions. 
*/

class Mailgun{
        
    private $apiKey;
    protected $workingDomain;
    protected $restClient;
    
    public function __construct($apiKey = null, $apiEndpoint = "api.mailgun.net", $apiVersion = "v2", $ssl = true){
	    $this->restClient = new RestClient($apiKey, $apiEndpoint, $apiVersion, $ssl);
    }

	public function sendMessage($workingDomain, $postData, $postFiles = array()){
	
	/* 
       This function allows the sending of a fully formed message OR a custom
	   MIME string. If sending MIME, the string must be passed in to the 3rd 
	   position of the function call. 
	*/
	
	    if(is_array($postFiles)){
			return $this->post("$workingDomain/messages", $postData, $postFiles);    
	    }
	    else if(is_string($postFiles)){
	    	try{
	    		$tempFile = tempnam(sys_get_temp_dir(), "MG_TMP_MIME");
	    		$fileHandle = fopen($tempFile, "w");
	    		fwrite($fileHandle, $postFiles);
	    	}
	    	catch(Exception $ex){
		    	throw $ex;
	    	}
			$result = $this->post("$workingDomain/messages.mime", $postData, array("message" => $tempFile));
			return $result;
			fclose($fileName);
	    	unlink($fileName);
	    }
	    else{
			throw new MissingRequiredMIMEParameters(EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
	    }
	}

	public function post($endpointUrl, $postData = array(), $files = array()){
		return $this->restClient->post($endpointUrl, $postData, $files);
	}
	
	public function get($endpointUrl, $queryString = array()){
		return $this->restClient->get($endpointUrl, $queryString);
	}
	
	public function delete($endpointUrl){
		return $this->restClient->delete($endpointUrl);
	}
	
	public function put($endpointUrl, $putData){
		return $this->restClient->put($endpointUrl, $putData);
	}
    
	public function MessageBuilder(){
		return new MessageBuilder();
	}
	
	public function OptInHandler(){
		return new OptInHandler();
	}
	
	public function BatchMessage($workingDomain, $autoSend = true){
		return new BatchMessage($this->restClient, $workingDomain, $autoSend);
	}
}

?>