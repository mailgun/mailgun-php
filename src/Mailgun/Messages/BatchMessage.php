<?PHP

//BatchMessage.php - Extends the Message class and provides continuous recipient addition.

namespace Mailgun\Messages;

use Mailgun\Exceptions\NoDomainsConfigured;
use Mailgun\Exceptions\HTTPError;
use Mailgun\Connection\Exceptions\MissingRequiredMIMEParameters;


class BatchMessage extends MessageBuilder{

	protected $batchRecipientAttributes;
	protected $autoSend;

	public function __construct($httpBroker, $autoSend){
		parent::__construct($httpBroker);
		$this->batchRecipientAttributes = array();
		$this->autoSend = $autoSend;
	}

	public function addBatchRecipient($address, $attributes){
		//Check for maximum recipient count
		if($this->toRecipientCount == 1000){
			//If autoSend is off, do things here.
			if($this->autoSend == false){
				throw new HTTPError("Too many recipients for API");
			}
			else{
				$this->sendMessage();
			}
		}
		if(array_key_exists("first", $attributes)){
			$name = $attributes["first"];
			if(array_key_exists("last", $attributes)){
				$name = $attributes["first"] . " " . $attributes["last"];
			}
		}
		
		$addr = $name . " <" . $address . ">";
		
		if(isset($this->message["to"])){
			array_push($this->message["to"], $addr);
		}
		else{
			$this->message["to"] = array($addr);
		}
		$attributes["id"] = $this->toRecipientCount;
		$this->batchRecipientAttributes["$address"] = $attributes;
		$this->toRecipientCount++;
		return true;
	}
	
	public function sendMessage($message = array(), $files = array()){
		if(count($message) < 1){
			$message = $this->message;
			$files = $this->files;
		}
		if(array_key_exists("from", $message) && 
		   array_key_exists("to", $message) && 
		   array_key_exists("subject", $message) &&
		   (array_key_exists("text", $message) || array_key_exists("html", $message))){
						$this->message["recipient-variables"] = json_encode($this->batchRecipientAttributes);
				$response = $this->httpBroker->postRequest($this->endpointUrl, $message, $files);
				$this->batchRecipientAttributes = array();
				$this->toRecipientCount = 0;
				unset($this->message["to"]);
				return $response;
			}
		else{
		throw new MissingRequiredMIMEParameters("You are missing the minimum parameters to send a message.");
		}
	}		
}
?>