<?php
//require 'vendor/autoload.php';

require_once('Mailgun/autoload.php');
require('Mailgun/Common/Messages.php');

use Mailgun\Common;
use Mailgun\Exceptions\NoDomainsConfigured;
use Mailgun\Exceptions\HTTPError;



/*
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


require('Mailgun/Common/Messages.php');

$client = new Common\Client("key-6e4jujnt879vqn2gx702wov0kg2hl1a6", "trstx.com", true);
echo $client->sendMessage($email);

$message = new Mailgun\Common\Message();
$message->addToRecipient("travis@tswientek.com", "travis swientek");
$message->addCcRecipient("travis@trstx.com", "CC Recipient");
$message->addBccRecipient("travis@trstx.com", "BCC Recipient");
$message->setFromAddress("travis@tswientek.com", "From Name");
$message->setSubject("This is the subject of the message!");
$message->setTextBody("This is the text body of the message!");
$message->setHtmlBody("This is the html body of the message!");
$message->addAttachment("@GitHub_Logo.png");
$message->setTestMode("yes");
$message->setDkim("yes");
$message->setOpenTracking("yes");
$message->setClickTracking("yes");
$message->addCustomOption("o:myoption", "true");
$message->addCampaignId("askldf");

$email = $message->getMessage();
var_dump($email);
echo $client->sendMessage($email);
*/

$client = new Common\Client("key-6e4jujnt879vqn2gx702wov0kg2hl1a6", "trstx.com", false);
echo $client->sendMessage(array("from" => "travis@trstx.com", "to" => "travis@tswientek.com", "subject" => "This is the email subject!", "text" => "Hi from the SDK!"));


?>