<?PHP

namespace Mailgun\Tests\Unsubscribes;

use Mailgun\Tests\MailgunClientTest;

class UnsubscribeTest extends \Mailgun\Tests\MailgunTestCase{
	
	private $client; 
	
	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	

	}
	public function testAddAddress(){
		$unsub = $this->client->Unsubscribes();
		$response = $unsub->addAddress("test@samples.mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testDeleteAddress(){
		$unsub = $this->client->Unsubscribes();
		$response = $unsub->deleteAddress("test@samples.mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testGetAddress(){
		$unsub = $this->client->Unsubscribes();
		$response = $unsub->getAddress("test@samples.mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testGetAddresses(){
		$unsub = $this->client->Unsubscribes();
		$response = $unsub->getAddresses("1", "30");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}

}