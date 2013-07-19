<?php
//require 'vendor/autoload.php';
/*
require_once('Mailgun/autoload.php');

use Mailgun\Common;
use Mailgun\Exceptions\NoDomainsConfigured;
use Mailgun\Exceptions\HTTPError;


try{
	$client = new Common\Client("key-6e4jujnt879vqn2gx702wov0kg2hl1a6", "trstx.com");
}
catch (HTTPError $e) {
	echo "An HTTP error has occurred! Please try again later\r\n";
}
//Post a Message

	echo $client->postRequest(array('url' => 'trstx.com/messages'), array('from'=>'test@trstx.com', 'to'=>'travis.swientek@rackspace.com', 'subject' => 'test', 'text' => 'asdf', 'o:testmode'=>true));

	echo $client->getRequest(array('url' => 'trstx.com/unsubscribes'), array());
	echo $client->postRequest(array('url' => 'trstx.com/unsubscribes'), array('address' => 'travis@whatever.com', 'tag' => '*'));
	echo $client->postRequest(array('url' => 'trstx.com/bounces'), array('address' => 'travis@whatever.com'));
	echo $client->deleteRequest(array('url' => 'trstx.com/bounces/travis@whatever.com'));
*/

require('Mailgun/Common/Messages.php');

$message = new Mailgun\Common\Message();

$message->setCampaignId("My-Super-Awesome-Campaign");
var_dump($message->getCampaignId());





?>