<?php

namespace Mailgun\Messages;

use Mailgun\Messages\BatchMessage;
use Mailgun\Messages\MessageBuilder;

use Mailgun\Messages\Exceptions\MissingRequiredMIMEParameters;

class Messages{

	protected $restClient;
	protected $workingDomain;
	protected $endpointUrl;
	
	public function __construct($restClient){
		$this->restClient = $restClient;
		$this->workingDomain = $this->restClient->returnWorkingDomain();
		$this->endpointUrl = $this->workingDomain . "/messages";
	}
	
	public function send($message = array(), $files = array()){
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
			$response = $this->restClient->postRequest($this->endpointUrl, $message, $files);
			return $response;
		}
	}
	
	public function setMessage($message = array(), $files = array()){
		$this->message = $message;
		$this->files = $files;
	}

	public function MessageBuilder(){
		return new MessageBuilder($this->restClient);
	}
	
	public function BatchMessage($autoSend = true){
		return new BatchMessage($this->restClient, $autoSend);
	}
}
?>