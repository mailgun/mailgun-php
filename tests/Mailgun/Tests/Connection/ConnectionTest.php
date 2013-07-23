<?PHP
namespace Mailgun\Tests\Connection;

use Mailgun\Common\Client;

class ConnectionTest extends \Mailgun\Tests\MailgunTestCase{

	private $client;

	public function setUp(){ 
		$this->client = new Client(\DEFAULT_MG_API_KEY, \DEFAULT_MG_DOMAIN, false);	
	}
	public function testNewClientConnection(){	
		$result = $this->client->validateCredentials();
		$this->assertTrue($result);
	}

	public function testSendSimpleTestMessage(){
		$result = $this->client->sendMessage(array("from" => "Excited User <me@samples.mailgun.org>", "to" => "travis@tswientek.com", "subject" => "Hello", "text" => "PHP Unit Test Success!", "o:testmode" => true));
		$status = $result->getStatusCode();
		$this->assertEquals("200", $status);
	}
}

?>