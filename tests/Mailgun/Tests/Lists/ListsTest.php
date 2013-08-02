<?PHP

namespace Mailgun\Tests\Lists;

use Mailgun\Tests\MailgunClientTest;

class ListsTest extends \Mailgun\Tests\MailgunTestCase{
	
	private $client; 
	
	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	

	}
	
	public function testGetLists(){
		$client = $this->client->Lists();
		$response = $client->getLists("1", "30");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	
	public function testGetList(){
		$client = $this->client->Lists();
		$response = $client->getList("mylist@mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	
	public function testAddList(){
		$client = $this->client->Lists();
		$response = $client->addList("mylist@mailgun.org", "My Sample List", "More Description Stuff", "readonly");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	
	public function testUpdateList(){
		$client = $this->client->Lists();
		$response = $client->updateList("mylist@mailgun.org", "My Sample List", "More Description Stuff", "readonly");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	
	public function testDeleteList(){
		$client = $this->client->Lists();
		$response = $client->deleteList(12345);
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	
	public function testGetListMembers(){
		$client = $this->client->Lists();
		$response = $client->getListMembers("mylist@mailgun.org", array('subscribed'=>true, 'limit' => 50, 'skip' => 50));
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}	

	public function testGetListMember(){
		$client = $this->client->Lists();
		$response = $client->getListMember("mylist@mailgun.org", "subscribee@mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}	
	
	public function testAddListMember(){
		$client = $this->client->Lists();
		$response = $client->getListMember("mylist@mailgun.org", "subscribee@mailgun.org", "Sample User", array('userid' => 'ABC123'), true, true);
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}	
	
	public function testUpdateListMember(){
		$client = $this->client->Lists();
		$response = $client->updateListMember("mylist@mailgun.org", "subscribee@mailgun.org", "Sample User", array('userid' => 'ABC123'), true);
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}

	public function testDeleteListMember(){
		$client = $this->client->Lists();
		$response = $client->deleteListMember("mylist@mailgun.org", "subscribee@mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}

	public function testGetListStats(){
		$client = $this->client->Lists();
		$response = $client->getListStats("mylist@mailgun.org");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}	
}