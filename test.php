<?
//require 'vendor/autoload.php';

require_once('file.php');

use Mailgun\Client;

$client = new Client($api_key="key-6e4jujnt879vqn2gx702wov0kg2hl1a6", $domain="trstx.com");
echo $client->sendSimpleMessage(array('from'=>'test@trstx.com', 'to'=>'travis.swientek@rackspace.com', 'subject' => 'test', 'text' => 'asdf', 'o:testmode'=>true));

?>