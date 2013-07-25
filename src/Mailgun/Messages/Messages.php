<?php

/*
 *	Message.php - Message builder for creating a message object. Pass the message object to the client to send the message.
*/
namespace Mailgun\Messages;

use Mailgun\Messages\MessageBuilder;
use Mailgun\Messages\BatchMessage;

use Mailgun\Messages\Exceptions\TooManyParameters;
use Mailgun\Messages\Expcetions\InvalidParameter;
use Mailgun\Messages\Expcetions\InvalidParameterType;

class Messages{

	protected $httpBroker;
	protected $workingDomain;
	protected $endpointUrl;
	
	public function __construct($httpBroker){
		$this->httpBroker = $httpBroker;
		$this->workingDomain = $this->httpBroker->returnWorkingDomain();
		$this->endpointUrl = $this->workingDomain . "/messages";
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
				$response = $this->httpBroker->postRequest($this->endpointUrl, $message, $files);
				return $response;
			}
		else{
		throw new MissingRequiredMIMEParameters("You are missing the minimum parameters to send a message.");
		}
	}
	
	public function setMessage($message = array(), $files = array()){
		$this->message = $message;
		$this->files = $files;
	}

	public function MessageBuilder(){
		return new MessageBuilder($this->httpBroker);
	}
	public function BatchMessage($autoSend = true){
		return new BatchMessage($this->httpBroker, $autoSend);
	}
}
?>