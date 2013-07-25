<?PHP

namespace Mailgun\Tests\Unsubscribes;

use Mailgun\Tests\MailgunClientTest;

class UnsubscribeTest extends \Mailgun\Tests\MailgunTestCase{
	
	private $client; 
	
	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	

	}
	public function testAddAddress(){
		$client = $this->client->Unsubscribes();
		$response = $client->addAddress("test@samples.mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testDeleteAddress(){
		$client = $this->client->Unsubscribes();
		$response = $client->deleteAddress("test@samples.mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testGetAddress(){
		$client = $this->client->Unsubscribes();
		$response = $client->getUnsubscribe("test@samples.mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testGetAddresses(){
		$client = $this->client->Unsubscribes();
		$response = $client->getUnsubscribes("1", "30");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}

}