<?PHP

namespace Mailgun\Tests\Bounces;

use Mailgun\Tests\MailgunClientTest;

class BouncesTest extends \Mailgun\Tests\MailgunTestCase{
	
	private $client; 
	
	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	

	}
	public function testAddAddress(){
		$client = $this->client->Bounces();
		$response = $client->addAddress("test@samples.mailgun.org", 550, "This bounced!");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testDeleteAddress(){
		$client = $this->client->Bounces();
		$response = $client->deleteAddress("test@samples.mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testGetAddress(){
		$client = $this->client->Bounces();
		$response = $client->getBounce("test@samples.mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testGetAddresses(){
		$client = $this->client->Bounces();
		$response = $client->getBounces("1", "30");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}

}