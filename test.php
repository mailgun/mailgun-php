<?php
//require 'vendor/autoload.php';

require 'vendor/autoload.php';

use Mailgun\MailgunClient;

use Mailgun\Connection\Exceptions\NoDomainsConfigured;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\MissingRequiredMIMEParameters;
use Mailgun\Connection\Exceptions\GenericHTTPError;


$client = new MailgunClient("key-6e4jujnt879vqn2gx702wov0kg2hl1a6", "trstx.com", false);
$MessageBuilder = $client->Messages()->MessageBuilder();
/*
$message = $client->MessageBuilder();

$message->setFromAddress("travis@tswientek.com", array("first"=>"first"));

$message->addToRecipient("travis@tswientek.com", array("first"=>"first"));
$message->setSubject("%recipient.first%, This is the subject of the message!");
$message->setTextBody("%recipient.first%, This is the text body of the message!");
$message->setHtmlBody("%recipient.first%, %recipient.my.id% This is the html body of the message!");
$message->addAttachment("@mailgun_icon.png");
$message->addAttachment("@rackspace_logo.jpg");
$message->setTestMode("yes");
$message->setDkim("yes");
//$message->setDeliveryTime("January 15, 2014 8:00AM", "CST");
$message->setOpenTracking("yes");
$message->setClickTracking("yes");
$message->addCustomOption("o:myoption", "true");
$message->addCampaignId("askldf");
$message->addCustomData("mycustomdata", array("name"=> "travis"));

echo count(array());
*/


var_dump($client->Unsubscribes()->addAddress("travis@myreallycrappydomain.com"));
var_dump($client->Unsubscribes()->getAddress("travis@myreallycrappydomain.com"));
var_dump($client->Unsubscribes()->getAddresses(50, 2));

/*
var_dump($message->getMessage());
var_dump($message->getFiles());

var_dump($message->sendMessage());
*/
//$message->addBatchRecipient("travis@tswientek.com", array("first" => "Travis", "last" => "Swientek", "my.id" => "ABC12345"));
/*

for($i = 0; $i<5; $i++){
	$message->addBatchRecipient("travis@".$i."test.com", array("first" => "$i - First", "last" => "$i - Last", "my.id" => "ABC12345"));
}

$message->endBatchMessage();


//echo $client->sendMessage($message->getMessage())->getBody();

*/
?>