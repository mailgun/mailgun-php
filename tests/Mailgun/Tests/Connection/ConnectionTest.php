<?PHP
namespace Mailgun\Tests\Connection;

use Mailgun\Tests\MailgunTest;

class ConnectionTest extends \Mailgun\Tests\MailgunTestCase{

	private $client;

	public function setUp(){ 
	}
	public function testNewClientInstantiation(){	
		$this->client = new MailgunTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	
	}
}

?>