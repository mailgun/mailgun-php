<?PHP

namespace Mailgun\Messages;

use Mailgun\Messages\Expcetions\InvalidParameter;
use Mailgun\Messages\Exceptions\TooManyParameters;
use Mailgun\Messages\Expcetions\InvalidParameterType;

class MessageBuilder{

	protected $message = array();
	protected $files = array();
	protected $sanitized;
	protected $toRecipientCount = 0;
	protected $ccRecipientCount = 0;
	protected $bccRecipientCount = 0;
	protected $attachmentCount = 0;
	protected $campaignIdCount = 0;
	protected $customOptionCount = 0;
	protected $tagCount = 0;
	
	
	public function __get($name){
		return $this->message;
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
	
	public function setSubject($subject = NULL){
		if($subject == NULL || $subject == ""){
			$subject = " ";
		}
		$this->message['subject'] = $subject;
		return true;
	}
	
	public function addCustomHeader($headerName, $headerData){
		if(!preg_match("/^h:/i", $headerName)){
			$headerName = "h:" . $headerName;
		}
		$this->message[$headerName] = array($headerData);
		return true;
	}
	
	public function setTextBody($textBody){
		if($textBody == NULL || $textBody == ""){
			$textBody = " ";
		}
		$this->message['text'] = $textBody;
		return true;
	}
	
	public function setHtmlBody($htmlBody){
		if($htmlBody == NULL || $htmlBody == ""){
			$htmlBody = " ";
		}
		$this->message['html'] = $htmlBody;
		return true;
	}
	
	public function addAttachment($attachmentPath){
		if(preg_match("/^@/", $attachmentPath)){
			if(isset($this->files["attachment"])){
				array_push($this->files["attachment"], $attachmentPath);
				}	
				else{
					$this->files["attachment"] = array($attachmentPath);
				}
			return true;
		}
		else{
			throw new InvalidParameter("Attachments must be passed with an \"@\" preceding the file path. Web resources not supported.");
		}
	}
	
	public function addInlineImage($inlineImagePath){
		if(preg_match("/^@/", $inlineImagePath)){
			if(isset($this->files['inline'])){
				array_push($this->files['inline'] , $inlineImagePath);
				return true;
				}
			else{
				$this->files['inline'] = array($inlineImagePath);
				return true;
			}
		}
		else{
			throw new InvalidParameter("Inline images must be passed with an \"@\" preceding the file path. Web resources not supported.");
		}
	}
	
	public function setTestMode($testMode){
		if(filter_var($testMode, FILTER_VALIDATE_BOOLEAN)){
			$testMode = "yes";
		}
		else{
			$testMode = "no";
		}
		$this->message['o:testmode'] = $testMode;
		return true;
	}
	
	public function addCampaignId($campaignId){
		if($this->campaignIdCount < 3){
			if(isset($this->message['o:campaign'])){
				array_push($this->message['o:campaign'] , $campaignId);
			}
			else{
				$this->message['o:campaign'] = array($campaignId);
			}
			$this->campaignIdCount++;
		return true;	
		}
		else{
			throw new TooManyParameters("You've exceeded the maximum (3) campaigns for a single message.");
		}
	}
	
	public function addTag($tag){
		if($this->tagCount < 3){
			if(isset($this->message['o:tag'])){
				array_push($this->message['o:tag'] , $tag);
			}
			else{
				$this->message['o:tag'] = array($tag);
			}
			$this->tagCount++;
		return true;	
		}
		else{
			throw new TooManyParameters("You've exceeded the maximum (3) tags for a single message.");
		}
	}
	
	public function setDkim($enabled){
		if(filter_var($enabled, FILTER_VALIDATE_BOOLEAN)){
			$enabled = "yes";
		}
		else{
			$enabled = "no";
		}
		$this->message["o:dkim"] = $enabled;
		return true;
	}
	
	public function setOpenTracking($enabled){
		if(filter_var($enabled, FILTER_VALIDATE_BOOLEAN)){
			$enabled = "yes";
		}
		else{
			$enabled = "no";
		}
		$this->message['o:tracking-opens'] = $enabled;
		return true;
	}
	
	public function setClickTracking($enabled){
		if(filter_var($enabled, FILTER_VALIDATE_BOOLEAN)){
			$enabled = "yes";
		}
		else{
			$enabled = "no";
		}
		$this->message['o:tracking-clicks'] = $enabled;
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
	
	public function addCustomParameter($parameterName, $data){
		if(isset($this->message[$parameterName])){
			array_push($this->message[$parameterName], $data);
			return true;
		}
		else{
			$this->message[$parameterName] = array($data);
			return true;
		}
	}
	
	public function setMessage($message = array(), $files = array()){
		$this->message = $message;
		$this->files = $files;
	}

	public function getMessage(){
		return $this->message;
	}

	public function getFiles(){
		return $this->files;
	}
}
?>