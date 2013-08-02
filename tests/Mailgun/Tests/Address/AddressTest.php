<?PHP

namespace Mailgun\Tests\Address;

use Mailgun\Tests\MailgunClientTest;

class AddressTest extends \Mailgun\Tests\MailgunTestCase{
	
	private $client; 
	
	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	

	}
	
	public function testValidateAddress(){
		$client = $this->client->Address();
		$response = $client->validateAddress("addressvalidation@mailgun.com");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
}