<?PHP

namespace Mailgun\Tests\Complaints;

use Mailgun\Tests\MailgunClientTest;

class ComplaintsTest extends \Mailgun\Tests\MailgunTestCase{
	
	private $client; 
	
	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	

	}
	public function testAddAddress(){
		$client = $this->client->Complaints();
		$response = $client->addAddress("test@samples.mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testDeleteAddress(){
		$client = $this->client->Complaints();
		$response = $client->deleteAddress("test@samples.mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testGetAddress(){
		$client = $this->client->Complaints();
		$response = $client->getComplaint("test@samples.mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testGetAddresses(){
		$client = $this->client->Complaints();
		$response = $client->getComplaints("1", "30");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}

}