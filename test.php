<?php
//require 'vendor/autoload.php';

require_once('Mailgun/autoload.php');
require('Mailgun/Common/Message.php');
require('Mailgun/Common/BatchMessage.php');

use Mailgun\Common;
use Mailgun\Exceptions\NoDomainsConfigured;
use Mailgun\Exceptions\HTTPError;


$client = new Common\Client("key-ca6d168e492611df8307001d60d24a9c-0b27e", "aawdawdad.ninomail.com", true);

$message = new Mailgun\Common\Message(array('from' =>"travis@aawdawdad.ninomail.com", 'to' => "travis@tswientek.com", "subject" => "subject here", "text" => "hello"));

$response = $client->sendMessage($message->getMessage());
echo $response->getBody();


//$message = new Mailgun\Common\Message($client);
/*
$message = new Mailgun\Common\BatchMessage($client, true);

$message->setFromAddress("travis@tswientek.com", "From Name");
$message->setSubject("%recipient.first%, This is the subject of the message!");
$message->setTextBody("%recipient.first%, This is the text body of the message!");
$message->setHtmlBody("%recipient.first%, %recipient.my.id% This is the html body of the message!");
$message->addAttachment("@GitHub_Logo.png");
$message->addAttachment("@batman-logo-big.gif");
$message->setTestMode("yes");
$message->setDkim("yes");
//$message->setDeliveryTime("January 15, 2014 8:00AM", "CST");
$message->setOpenTracking("yes");
$message->setClickTracking("yes");
$message->addCustomOption("o:myoption", "true");
$message->addCampaignId("askldf");
$message->addCustomData("mycustomdata", array("name"=> "travis"));

//echo $message->sendMessage();
//$message->addBatchRecipient("travis@tswientek.com", array("first" => "Travis", "last" => "Swientek", "my.id" => "ABC12345"));


for($i = 0; $i<5; $i++){
	$message->addBatchRecipient("travis@".$i."test.com", array("first" => "$i - First", "last" => "$i - Last", "my.id" => "ABC12345"));
}

$message->endBatchMessage();


//echo $client->sendMessage($message->getMessage())->getBody();

*/
?>