<?PHP

//BatchMessage.php - Extends the Message class and provides continuous recipient addition.

namespace Mailgun\Common;
	
require_once 'Globals.php';

use Guzzle\Http\Client as Guzzler;
use Mailgun\Exceptions\NoDomainsConfigured;
use Mailgun\Exceptions\HTTPError;

class BatchMessage extends Message{

	private $batchRecipientAttributes;
	private $client;

	public function __construct($client){
		parent::__construct($this->client);
		$this->batchRecipientAttributes = array();
		$this->client = $client;
	}

	public function addBatchRecipient($address, $attributes){
		if($this->toRecipientCount == 1000){
			$this->sendBatchMessage();
			$this->batchRecipientAttributes = array();
		    $this->toRecipientCount = 0;
		   	unset($this->message['to']);
		}
		if(array_key_exists("first", $attributes)){
			if(array_key_exists("last", $attributes)){
				$name = $attributes["first"] . " " . $attributes["last"];
				}
			$name = $attributes["first"];
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
	
	public function endBatchMessage(){
		$this->sendBatchMessage();
		$this->batchRecipientAttributes = array();
	    $this->toRecipientCount = 0;
	   	unset($this->message['to']);
	}
	private function sendBatchMessage(){
		if(array_key_exists("from", $this->message)){
			if($this->toRecipientCount > 0){
				if(array_key_exists("subject", $this->message)){
					if(array_key_exists("text", $this->message) || array_key_exists("html", $this->message)){
						$this->message["recipient-variables"] = json_encode($this->batchRecipientAttributes);
						return $this->client->sendMessage($this->message);
					}
				}
			}
		}	
	}
}
?>