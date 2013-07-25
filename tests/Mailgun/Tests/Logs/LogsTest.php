<?PHP

namespace Mailgun\Tests\Logs;

use Mailgun\Tests\MailgunClientTest;

class LogsTest extends \Mailgun\Tests\MailgunTestCase{
	
	private $client; 
	
	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	

	}
	public function testGetLogs(){
		$client = $this->client->Logs();
		$response = $client->getLogs("1", "30");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}

}