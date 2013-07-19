<?PHP

//Message.php - Getters and setters for sending a message. 

namespace Mailgun\Common;
	
require_once 'Globals.php';

use Guzzle\Http\Client as Guzzler;
use Mailgun\Exceptions\NoDomainsConfigured;
use Mailgun\Exceptions\HTTPError;

class Message{

	private $message = array();
	private $toRecipient = array();
	private $ccRecipient = array();
	private $bccRecipient = array();
	private $fromAddress;
	private $subject;
	private $customHeader = array();
	private $textBody;
	private $htmlBody;
	private $attachment = array();
	private $inlineImage = array();
	private $options = array();
	private $customData = array();
	private $customOption = array();
	
	
	public function __construct($headers = NULL, $content = NULL, $options = NULL){


	}

	/*
	This section includes all headers that can be programmatically
	added to the email. Each attribute is broken down in to a single
	function to make it easier and more intuitive for new users. 
	Dealing with complex arrays on the client side is usually no fun. 
	Plus most people iterate through an array of data from a database, so
	why not just iterate and add each recipient to the "Message" object instead?
	*/	

	// This function adds a recipient item to the recipient array. If the name is Null,
	// the address will be included in the typical name field so it displays nicely.
	public function addToRecipient($address, $name = NULL){
		if($name != NULL){
			$addr = $name . " <" . $address . ">";
			array_push($this->toRecipient, $addr);
			return true;
		}
		else{
			$addr = $address . " <" . $address . ">";
			array_push($this->toRecipient, $addr);
			return true;
		}
	}
	public function addCcRecipient($address, $name = NULL){
		if($name != NULL){
			$addr = $name . " <" . $address . ">";
			array_push($this->ccRecipient, $addr);
			return true;
		}
		else{
			$addr = $address . " <" . $address . ">";
			array_push($this->ccRecipient, $addr);
			return true;
		}
		
	}
	public function addBccRecipient($address, $name = NULL){
		if($name != NULL){
			$addr = $name . " <" . $address . ">";
			array_push($this->bccRecipient, $addr);
			return true;
		}
		else{
			$addr = $address . " <" . $address . ">";
			array_push($this->bccRecipient, $addr);
			return true;
		}
	}
	public function setFromAddress($address, $name = NULL){
		if($name != NULL){
			$this->fromAddress = $name . " <" . $address . ">";
			return true;
		}
		else{
			$this->fromAddress = $address . " <" . $address . ">";
			return true;
		}
	}
	public function setSubject($data = NULL){
		if($data != NULL){
			$this->subject = $data;
			return true;
		}
		else{
			$this->subject = "";
			return false;
		}
	}
	public function addCustomHeader($data){
		//Need to check if "X-Mailgun" exists via Regular Expression. Then either add it or not. 
		//if(preg_match("\^X-Mailgun", $data)){
		if(true){	
			array_push($this->customHeader, $data);
			return true;
		}
		else{
			$header = "X-Mailgun-" . $data;
			array_push($this->customHeader, $header);
			return true;
		}
		return;		
	}
	
	//Content
	public function setTextBody($data){
		//Not sure what validation we should do here. Just assigning the data for now. 
		$this->textBody = $data;
		return true;
		
	}
	public function setHTMLBody($data){
		//Not sure what validation we should do here. Just assigning the data for now. 
		$this->htmlBody = $data;
		return true;
		
	}
	public function addAttachment($data){
		$postFields["attachment[$j]"] ="@/path-to-doc/".$mailObj["filenames"][$j]; 		
		
	}
	public function addInlineImage(){}
	
	//Options
	public function setTestMode($data){
		if(is_bool($data)){
			if($data == true){
				array_push($this->options, array("o:testmode" => true));
			}
		}
		return;
	}
	public function setCampaignId($data){
		if(is_array(isset($this->options['o:campaign']))){
			$arrCount = count($this->options['o:campaign']);
			if($arrCount <= 3){
				$this->options['o:campaign'] = $data;
			}
			else{
				return false;
				}		
			}
		else {
			$this->options['o:campaign'] = $data;
			return true;
		}
	}
	public function setDKIM(){
		if(is_bool($data)){
			if($data == true){
				array_push($this->options, array("o:dkim" => true));
			}
			else{
				array_push($this->options, array("o:dkim" => false));
			}
		}
		return;
	}
	public function setOpenTracking($data){
		if(is_bool($data)){
			if($data == true){
				array_push($this->options, array("o:tracking-opens" => true));
			}
			else{
				array_push($this->options, array("o:tracking-opens" => false));
			}
		}
		return;
	}	
	public function setClickTracking($data){
		if(is_bool($data)){
			if($data == true){
				array_push($this->options, array("o:tracking-clicks" => true));
			}
			else{
				array_push($this->options, array("o:tracking-clicks" => false));
			}
		}
		return;
	}
	
	//Handlers for any new features not defined as a concrete function.
	public function addCustomData(){}
	public function addCustomOptions(){}
	
	public function sendMessage(){
		// This is the grand daddy function to send the message and flush all data from variables. 
		
		
	}
	
	public function getToRecipients(){
		return $this->toRecipient;
	}
		
	public function getCcRecipients(){
		return $this->ccRecipient;
	}
	public function getBccRecipients(){
		return $this->bccRecipient;
	}
	public function getFromAddress(){
		return $this->bccRecipient;
	}
	public function getSubject(){
		return $this->subject;
	}
	public function getTextBody(){
		return $this->textBody;
	}
	public function getHTMLBody(){
		return $this->htmlBody;
	}		
	public function getCampaignId(){
		return $this->options;
	}				
}































?>