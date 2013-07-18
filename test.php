<?
//require 'vendor/autoload.php';

require_once('Mailgun/autoload.php');

use Mailgun\Common;
use Mailgun\Exceptions\NoDomainsConfigured;
use Mailgun\Exceptions\HTTPError;

$client = new Common\Client("key-6e4jujnt879vqn2gx702wov0kg2hl1a6", "trstx.com");

try{
	echo $client->validateCredentials();
}
catch (HTTPError $e) {
	echo "An HTTP error has occurred! Please try again later\r\n";
}

//echo $client->postRequest(array('url' => 'trstx.com/messages'), array('from'=>'test@trstx.com', 'to'=>'travis.swientek@rackspace.com', 'subject' => 'test', 'text' => 'asdf', 'o:testmode'=>true));



?>