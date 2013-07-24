<?PHP

/*
 *	Message.php - Message builder for creating a message object. Pass the message object to the client to send the message.
*/
namespace Mailgun\Messages;
	
use Guzzle\Http\Client as Guzzler;

use Mailgun\Messages\Exceptions\TooManyParameters;
use Mailgun\Messages\Expcetions\InvalidParameter;
use Mailgun\Messages\Expcetions\InvalidParameterType;

class Message{

	protected $message;
	protected $sanitized;
	protected $toRecipientCount;
	protected $ccRecipientCount;
	protected $bccRecipientCount;
	protected $attachmentCount;
	protected $campaignIdCount;
	protected $customOptionCount;
	
	public function __construct(){
		$this->message = array();
	    $this->toRecipientCount = 0;
	    $this->ccRecipientCount = 0;
	    $this->bccRecipientCount = 0;
		$this->attachmentCount = 0;
		$this->campaignIdCount = 0;
		$this->customOptionCount = 0;
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
				$addr = $name . " <" . $address . ">";
			}
			else{
				$addr = $address;
			}
			if(isset($this->message["to"])){
				array_push($this->message["to"], $addr);
			}
			else{
				$this->message["to"] = array($addr);
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
				$addr = $name . " <" . $address . ">";
			}
			else{
				$addr = $address;
			}
			if(isset($this->message["cc"])){
				array_push($this->message["cc"], $addr);
			}
			else{
				$this->message["cc"] = array($addr);
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
				$addr = $name . " <" . $address . ">";
			}
			else{
				$addr = $address;
			}
			if(isset($this->message["bcc"])){
				array_push($this->message["bcc"], $addr);
			}
			else{
				$this->message["bcc"] = array($addr);
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
			$addr = $name . " <" . $address . ">";
		}
		else{
			$addr = $address;
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
			if(isset($this->message["attachment"])){
				array_push($this->message["attachment"], $data);
				}	
				else{
					$this->message["attachment"] = array($data);
				}
			return true;
		}
		else{
			throw new InvalidParameter("Attachments must be passed with an \"@\" preceding the file path. Web resources not supported.");
		}
	}
	public function addInlineImage($data){
		if(preg_match("/^@/", $data)){
			if(isset($this->message['inline'])){
				array_push($this->message['inline'] , $data);
				return true;
				}
			else{
				$this->message['inline'] = array($data);
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
}
?>