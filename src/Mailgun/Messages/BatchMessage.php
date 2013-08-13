<?PHP

namespace Mailgun\Messages;

use Mailgun\Messages\MessageBuilder;
use Mailgun\Messages\Exceptions\TooManyParameters;
use Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters;

/* 
   This class is used for batch sending. See the official documentation
   for usage instructions. 
*/

class BatchMessage extends MessageBuilder{

	private $batchRecipientAttributes;
	private $autoSend;
	private $restClient;
	private $workingDomain;

	public function __construct($restClient, $workingDomain, $autoSend){
		$this->batchRecipientAttributes = array();
		$this->autoSend = $autoSend;
		$this->restClient = $restClient;
		$this->workingDomain = $workingDomain;
		$this->endpointUrl = $workingDomain . "/messages";
	}

	public function addToRecipient($address, $variables = null){
		if($this->toRecipientCount == RECIPIENT_COUNT_LIMIT){
			if($this->autoSend == false){
				throw new TooManyParameters(TOO_MANY_RECIPIENTS);
			}
			$this->sendMessage();
		}
		
		$this->addRecipient("to", $address, $variables);
		$attributes["id"] = $this->toRecipientCount;
		$this->batchRecipientAttributes["$address"] = $variables;
		$this->toRecipientCount++;
	}
	
	public function sendMessage($message = array(), $files = array()){
		if(count($message) < 1){
			$message = $this->message;
			$files = $this->files;
		}
		if(!array_key_exists("from", $message)){
			throw new MissingRequiredMIMEParameters(EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
		}
		elseif(!array_key_exists("to", $message)){
			throw new MissingRequiredMIMEParameters(EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
		}
		elseif(!array_key_exists("subject", $message)){
			throw new MissingRequiredMIMEParameters(EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
		}
		elseif((!array_key_exists("text", $message) && !array_key_exists("html", $message))){
			throw new MissingRequiredMIMEParameters(EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
		}
		else{		
			$this->message["recipient-variables"] = json_encode($this->batchRecipientAttributes);
			$response = $this->restClient->post($this->endpointUrl, $message, $files);
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