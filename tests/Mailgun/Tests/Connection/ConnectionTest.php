<?PHP
namespace Mailgun\Tests\Connection;

use Mailgun\Tests\MailgunClientTest;

class ConnectionTest extends \Mailgun\Tests\MailgunTestCase{

	private $client;

	public function setUp(){ 
	}
	public function testNewClientInstantiation(){	
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	
	}
}

?>