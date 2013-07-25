<?PHP

namespace Mailgun\Tests\Stats;

use Mailgun\Tests\MailgunClientTest;

class StatsTest extends \Mailgun\Tests\MailgunTestCase{
	
	private $client; 
	
	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	

	}
	public function testDeleteTag(){
		$client = $this->client->Stats();
		$response = $client->deleteTag("My-Tag");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testGetStats(){
		$client = $this->client->Stats();
		$response = $client->getStats("1", "30", "Sent", "10/10/10");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}

}