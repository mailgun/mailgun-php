<?PHP

namespace Mailgun\Messages;

use Mailgun\Messages\Expcetions\InvalidParameter;
use Mailgun\Messages\Exceptions\TooManyParameters;
use Mailgun\Messages\Expcetions\InvalidParameterType;

class MessageBuilder extends Messages{

	protected $message = array();
	protected $files = array();
	protected $sanitized;
	protected $toRecipientCount = 0;
	protected $ccRecipientCount = 0;
	protected $bccRecipientCount = 0;
	protected $attachmentCount = 0;
	protected $campaignIdCount = 0;
	protected $customOptionCount = 0;
	protected $httpBroker;
	
	public function __construct($httpBroker){
		parent::__construct($httpBroker);
		$this->httpBroker = $httpBroker;
	}

	public function addToRecipient($address, $attributes){
		if($this->toRecipientCount < 1000){
			if(is_array($attributes)){
				if(array_key_exists("first", $attributes)){
					$name = $attributes["first"];
					if(array_key_exists("last", $attributes)){
						$name = $attributes["first"] . " " . $attributes["last"];
					}
				}
			}
			if(isset($name)){
				$compiledAddress = $name . " <" . $address . ">";
			}
			else{
				$compiledAddress = $address;
			}
			if(isset($this->message["to"])){
				array_push($this->message["to"], $compiledAddress);
			}
			else{
				$this->message["to"] = array($compiledAddress);
			}
			$this->toRecipientCount++;
			return true;
		}
		else{
			throw new TooManyParameters("You've exceeded the maximum recipient count (1,000) on the to field.");
		}
	}

	public function addCcRecipient($address, $attributes){
		if($this->ccRecipientCount < 1000){
			if(is_array($attributes)){
				if(array_key_exists("first", $attributes)){
					$name = $attributes["first"];
					if(array_key_exists("last", $attributes)){
						$name = $attributes["first"] . " " . $attributes["last"];
					}
				}
			}
			if(isset($name)){
				$compiledAddress = $name . " <" . $address . ">";
			}
			else{
				$compiledAddress = $address;
			}
			if(isset($this->message["cc"])){
				array_push($this->message["cc"], $compiledAddress);
			}
			else{
				$this->message["cc"] = array($compiledAddress);
			}
			$this->ccRecipientCount++;
			return true;
		}
		else{
			throw new TooManyParameters("You've exceeded the maximum recipient count (1,000) on the cc field.");
		}
	}
	
	public function addBccRecipient($address, $attributes){
		if($this->bccRecipientCount < 1000){
			if(is_array($attributes)){
				if(array_key_exists("first", $attributes)){
					$name = $attributes["first"];
					if(array_key_exists("last", $attributes)){
						$name = $attributes["first"] . " " . $attributes["last"];
					}
				}
			}
			if(isset($name)){
				$compiledAddress = $name . " <" . $address . ">";
			}
			else{
				$compiledAddress = $address;
			}
			if(isset($this->message["bcc"])){
				array_push($this->message["bcc"], $compiledAddress);
			}
			else{
				$this->message["bcc"] = array($compiledAddress);
			}
			$this->bccRecipientCount++;
			return true;
		}
		else{
			throw new TooManyParameters("You've exceeded the maximum recipient count (1,000) on the bcc field.");
		}
	}
	
	public function setFromAddress($address, $attributes){
		if(isset($attributes)){
			if(is_array($attributes)){
				if(array_key_exists("first", $attributes)){
					$name = $attributes["first"];
					if(array_key_exists("last", $attributes)){
						$name = $attributes["first"] . " " . $attributes["last"];
					}
				}
			}
			else{
				throw new InvalidParameterType("The parameter you've passed in position 2 must be an array.");
			}
		}
		if(isset($name)){
			$compiledAddress = $name . " <" . $address . ">";
		}
		else{
			$compiledAddress = $address;
		}
		$this->message['from'] = $compiledAddress;
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
		$this->message[$headerName] = array($data);
		return true;
	}
	
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
		if(preg_match("/^@/", $data)){
			if(isset($this->files["attachment"])){
				array_push($this->files["attachment"], $data);
				}	
				else{
					$this->files["attachment"] = array($data);
				}
			return true;
		}
		else{
			throw new InvalidParameter("Attachments must be passed with an \"@\" preceding the file path. Web resources not supported.");
		}
	}
	
	public function addInlineImage($data){
		if(preg_match("/^@/", $data)){
			if(isset($this->files['inline'])){
				array_push($this->files['inline'] , $data);
				return true;
				}
			else{
				$this->files['inline'] = array($data);
				return true;
			}
		}
		else{
			throw new InvalidParameter("Inline images must be passed with an \"@\" preceding the file path. Web resources not supported.");
		}
	}
	
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
			if(isset($this->message['o:campaign'])){
				array_push($this->message['o:campaign'] , $data);
			}
			else{
				$this->message['o:campaign'] = array($data);
			}
			$this->campaignIdCount++;
		return true;	
		}
		else{
			throw new TooManyParameters("You've exceeded the maximum (3) campaigns for a single message.");
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
		if(isset($timeZone)){
			$timeZoneObj = new \DateTimeZone("$timeZone");
		}
		else{
			$timeZoneObj = new \DateTimeZone(\DEFAULT_TIME_ZONE);
		}
		
		$dateTimeObj = new \DateTime($timeDate, $timeZoneObj);
		$formattedTimeDate = $dateTimeObj->format(\DateTime::RFC2822);
		$this->message['o:deliverytime'] = $formattedTimeDate;
		return true;
	}
	
	public function addCustomData($customName, $data){
		if(is_array($data)){
			$jsonArray = json_encode($data);
			$this->message['v:'.$customName] = $jsonArray;
		}
		else{
			throw new InvalidParameter("Custom Data values must be passed as an array.");
			return false;
		}
		
	}
	
	public function addCustomOption($optionName, $data){
		if(!preg_match("/^o:/i", $optionName)){
			$optionName = "o:" . $optionName;
		}
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
	
	public function getFiles(){
		return $this->files;
	}
}
?>