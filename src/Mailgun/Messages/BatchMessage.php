<?PHP

namespace Mailgun\Messages;

use Mailgun\Messages\MessageBuilder;
use Mailgun\Messages\Exceptions\TooManyParameters;
use Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters;


class BatchMessage extends MessageBuilder{

	protected $batchRecipientAttributes;
	protected $autoSend;
	protected $restClient;
	protected $workingDomain;

	public function __construct($restClient, $workingDomain, $autoSend){
		$this->batchRecipientAttributes = array();
		$this->autoSend = $autoSend;
		$this->restClient = $restClient;
		$this->workingDomain = $workingDomain;
		$this->endpointUrl = $workingDomain . "/messages";
	}

	public function addToRecipient($address, $attributes){
		//Check for maximum recipient count
		if($this->toRecipientCount == 1000){
			//If autoSend is off, do things here.
			if($this->autoSend == false){
				throw new TooManyParameters("You've exceeded the maximum recipient count (1,000) on the to field.");
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
		
		$compiledAddress = $name . " <" . $address . ">";
		
		if(isset($this->message["to"])){
			array_push($this->message["to"], $compiledAddress);
		}
		else{
			$this->message["to"] = array($compiledAddress);
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
		if(!array_key_exists("from", $message)){
			throw new MissingRequiredMIMEParameters("You are missing the from parameter for your message.");
		}
		elseif(!array_key_exists("to", $message)){
			throw new MissingRequiredMIMEParameters("You are missing a recipient for your message.");
		}
		elseif(!array_key_exists("subject", $message)){
			throw new MissingRequiredMIMEParameters("You are missing the subject of the message.");
		}
		elseif((!array_key_exists("text", $message) && !array_key_exists("html", $message))){
			throw new MissingRequiredMIMEParameters("You are missing the body of the message.");
		}
		else{		
			$this->message["recipient-variables"] = json_encode($this->batchRecipientAttributes);
			$response = $this->restClient->postRequest($this->endpointUrl, $message, $files);
			$this->batchRecipientAttributes = array();
			$this->toRecipientCount = 0;
			unset($this->message["to"]);
			return $response;
		}
	}
	public function finalize(){
		return $this->sendMessage();
	}
}
?>