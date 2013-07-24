<?PHP

namespace Mailgun\Tests\Unsubscribes;

use Mailgun\Connection\Client;
use Mailgun\Unsubscribes\Unsubscribe;

class UnsubscribeTest extends \Mailgun\Tests\MailgunTestCase{
	
	private $client; 
	
	public function setUp(){ 
		$this->client = new Client(\DEFAULT_MG_API_KEY, \DEFAULT_MG_DOMAIN, false);	

	}
	public function testAddUnsubscribe(){
		$unsub = new Unsubscribe($this->client);
		$response = $unsub->addUnsubscribe("test@samples.mailgun.org");
		$httpCode = $response->getStatusCode();
		$this->assertEquals(200, $httpCode);
	}
	public function testDeleteUnsubscribe(){
		$unsub = new Unsubscribe($this->client);
		$response = $unsub->deleteUnsubscribe("test@samples.mailgun.org");
		$httpCode = $response->getStatusCode();
		$this->assertEquals(200, $httpCode);
	}

	
}