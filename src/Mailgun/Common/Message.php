<?PHP

/*
 *	Message.php - Message builder for creating a message object. Pass the message object to the client to send the message.
*/
namespace Mailgun\Common;
	
require_once 'Globals.php';

use Guzzle\Http\Client as Guzzler;
use Mailgun\Exceptions\NoDomainsConfigured;
use Mailgun\Exceptions\HTTPError;

class Message{

	protected $message;
	protected $sanitized;
	protected $toRecipientCount;
	protected $ccRecipientCount;
	protected $bccRecipientCount;
	protected $attachmentCount;
	protected $campaignIdCount;
	protected $customOptionCount;
	
	public function __construct($message = null){
		$this->message = array();
		if(isset($message)){
			$this->message = $message;
		}
	    $this->toRecipientCount = 0;
	    $this->ccRecipientCount = 0;
	    $this->bccRecipientCount = 0;
		$this->attachmentCount = 0;
		$this->campaignIdCount = 0;
		$this->customOptionCount = 0;
	}

	public function addToRecipient($address, $attributes){
		if($this->toRecipientCount < 1000){
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
			$this->toRecipientCount++;
			return true;
		}
	}

	public function addCcRecipient($address, $name = NULL){
		if($this->ccRecipientCount < 1000){
			if(array_key_exists("first", $attributes)){
				if(array_key_exists("last", $attributes)){
					$name = $attributes["first"] . " " . $attributes["last"];
					}
				$name = $attributes["first"];
			}
			
			$addr = $name . " <" . $address . ">";
			
			if(isset($this->message["cc"])){
				array_push($this->message["cc"], $addr);
			}
			else{
				$this->message["cc"] = array($addr);
			}
			$this->ccRecipientCount++;
			return true;
		}	
	}
	public function addBccRecipient($address, $name = NULL){
		if($this->bccRecipientCount < 1000){
			if(array_key_exists("first", $attributes)){
				if(array_key_exists("last", $attributes)){
					$name = $attributes["first"] . " " . $attributes["last"];
					}
				$name = $attributes["first"];
			}
			
			$addr = $name . " <" . $address . ">";
			
			if(isset($this->message["bcc"])){
				array_push($this->message["bcc"], $addr);
			}
			else{
				$this->message["bcc"] = array($addr);
			}
			$this->bccRecipientCount++;
			return true;
		}
	}
	public function setFromAddress($address, $name = NULL){
		if($name != NULL){
			$addr = $name . " <" . $address . ">";
		}
		else{
			$addr = $address . " <" . $address . ">";
		}
			$this->message['from'] = $addr;
			return true;
	}
	public function setSubject($data = NULL){
		if($data == NULL || $data == ""){
			$data = " ";
		}
		$this->message['subject'] = $data;
		return true;
	}
	public function addCustomHeader($headerName, $data){
		if(!preg_match("/^h:/i", $headerName)){
			$headerName = "h:" . $headerName;
		}
		
		$this->addCustomOption($headerName, $data);
		return true;
	}
	
	//Content
	public function setTextBody($data){
		if($data == NULL || $data == ""){
			$data = " ";
		}
		$this->message['text'] = $data;
		return true;
	}
	public function setHtmlBody($data){
		if($data == NULL || $data == ""){
			$data = " ";
		}
		$this->message['html'] = $data;
		return true;
	}
	public function addAttachment($data){
		if(isset($this->message["attachment"])){
			array_push($this->message["attachment"], $data);
		}
		else{
			$this->message["attachment"] = array($data);
		}
		return true;
	}
	public function addInlineImage($data){
		if(isset($this->message['inline'])){
			array_push($this->message['inline'] , $data);
			return true;
		}
		else{
			$this->message['inline'] = array($data);
			return true;
		}
	}
	
	//Options
	public function setTestMode($data){
		if(filter_var($data, FILTER_VALIDATE_BOOLEAN)){
			$data = "yes";
		}
		else{
			$data = "no";
		}
		$this->message['o:testmode'] = $data;
		return true;
	}
	public function addCampaignId($data){
		if($this->campaignIdCount < 3){
			$arr = "o:campaign[".$this->campaignIdCount."]";
			$this->message[$arr] = $data;
			$this->campaignIdCount++;
		return true;	
		}
	}
	public function setDkim($data){
		if(filter_var($data, FILTER_VALIDATE_BOOLEAN)){
			$data = "yes";
		}
		else{
			$data = "no";
		}
		$this->message["o:dkim"] = $data;
		return true;
	}
	public function setOpenTracking($data){
		if(filter_var($data, FILTER_VALIDATE_BOOLEAN)){
			$data = "yes";
		}
		else{
			$data = "no";
		}
		$this->message['o:tracking-opens'] = $data;
		return true;
	}	
	public function setClickTracking($data){
		if(filter_var($data, FILTER_VALIDATE_BOOLEAN)){
			$data = "yes";
		}
		else{
			$data = "no";
		}
		$this->message['o:tracking-clicks'] = $data;
		return true;
	}
	public function setDeliveryTime($timeDate, $timeZone = NULL){
		if($timeZone == NULL){
			$timeZoneObj = new \DateTimeZone(DEFAULT_TIME_ZONE);
		}
		else{
			$timeZoneObj = new \DateTimeZone("$timeZone");
		}
		
		$dateTimeObj = new \DateTime($timeDate, $timeZoneObj);
		$formattedTimeDate = $dateTimeObj->format(\DateTime::RFC2822);
		$this->message['o:deliverytime'] = $formattedTimeDate;
		return true;
	}
	//Handlers for any new features not defined as a concrete function.
	public function addCustomData($customName, $data){
		if(is_array($data)){
			$jsonArray = json_encode($data);
			$this->message['v:'.$customName] = $jsonArray;
		}
		else{
			//throw exception here!
			return false;
		}
		
	}
	public function addCustomOption($optionName, $data){
		if(isset($this->message['options'][$optionName])){
			array_push($this->message['options'][$optionName], $data);
			return true;
		}
		else{
			$this->message['options'][$optionName] = array($data);
			return true;
		}
	}
	public function getMessage(){
		return $this->message;
	}				
}
?>