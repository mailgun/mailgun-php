<?PHP

//Message.php - Getters and setters for sending a message. 

namespace Mailgun\Common;
	
require_once 'Globals.php';

use Guzzle\Http\Client as Guzzler;
use Mailgun\Exceptions\NoDomainsConfigured;
use Mailgun\Exceptions\HTTPError;

class Message{

	private $message;
	private $sanitized;
	private $toRecipientCount;
	private $ccRecipientCount;
	private $bccRecipientCount;
	private $attachmentCount;
	private $campaignIdCount;
	private $customOptionCount;
	
	public function __construct($message = null){

		$this->message = array();
	    $this->toRecipientCount = 0;
	    $this->ccRecipientCount = 0;
	    $this->bccRecipientCount = 0;
		$this->attachmentCount = 0;
		$this->campaignIdCount = 0;
		$this->customOptionCount = 0;
	}

	public function addToRecipient($address, $name = NULL){
		if($name != NULL){
			$addr = $name . " <" . $address . ">";
		}
		else{
			$addr = $address . " <" . $address . ">";
		}
		$arr = "to[".$this->toRecipientCount."]";
		$this->message[$arr] = $addr;
		$this->toRecipientCount++;
		return true;
	}
	public function addCcRecipient($address, $name = NULL){
		if($name != NULL){
			$addr = $name . " <" . $address . ">";
		}
		else{
			$addr = $address . " <" . $address . ">";
		}
		$arr = "cc[".$this->ccRecipientCount."]";
		$this->message[$arr] = $addr;
		$this->ccRecipientCount++;
		return true;
		
	}
	public function addBccRecipient($address, $name = NULL){
		if($name != NULL){
			$addr = $name . " <" . $address . ">";
		}
		else{
			$addr = $address . " <" . $address . ">";
		}
		$arr = "bcc[".$this->bccRecipientCount."]";
		$this->message[$arr] = $addr;
		$this->bccRecipientCount++;
		return true;
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
		$arr = "attachment[".$this->attachmentCount."]";
		$this->message[$arr] = $data;
		$this->attachmentCount++;
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
	
	public function setDeliveryTime($data){
		//BLAH
	}
	
	//Handlers for any new features not defined as a concrete function.
	public function addCustomData(){}
	
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