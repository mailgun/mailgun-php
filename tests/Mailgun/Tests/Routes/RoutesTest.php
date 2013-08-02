<?PHP

namespace Mailgun\Tests\Routes;

use Mailgun\Tests\MailgunClientTest;

class RoutesTest extends \Mailgun\Tests\MailgunTestCase{
	
	private $client; 
	
	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	

	}
	public function testAddRoute(){
		$client = $this->client->Routes();
		$response = $client->addRoute(10, "This is the description", "match_recipient('.*@gmail.com')", "forward('alex@mailgun.net')");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testDeleteRoute(){
		$client = $this->client->Routes();
		$response = $client->deleteRoute(12345);
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testGetRoute(){
		$client = $this->client->Routes();
		$response = $client->getRoute(12345);
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testGetRoutes(){
		$client = $this->client->Routes();
		$response = $client->getRoutes("5", "10");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	public function testUpdateRoute(){
		$client = $this->client->Routes();
		$response = $client->updateRoute(12345, 10, "This is the description", "match_recipient('.*@gmail.com')", "forward('alex@mailgun.net')");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}

}