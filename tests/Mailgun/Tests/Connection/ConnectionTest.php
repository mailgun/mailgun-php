<?PHP
namespace Mailgun\Tests\Connection;

use Mailgun\Tests\MailgunClientTest;

class ConnectionTest extends \Mailgun\Tests\MailgunTestCase{

	private $client;

	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	
	}
	public function testNewClientConnection(){	
		$result = $this->client->validateCredentials();
		$this->assertTrue($result);
	}
}

?>