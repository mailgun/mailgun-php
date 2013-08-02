<?PHP

namespace Mailgun\Tests\Campaigns;

use Mailgun\Tests\MailgunClientTest;

class CampaignsTest extends \Mailgun\Tests\MailgunTestCase{
	
	private $client; 
	
	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	

	}
	
	public function testGetCampaigns(){
		$client = $this->client->Campaigns();
		$response = $client->getCampaigns("1", "30");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	
	public function testGetCampaign(){
		$client = $this->client->Campaigns();
		$response = $client->getCampaign(12345);
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	
	public function testAddCampaign(){
		$client = $this->client->Campaigns();
		$response = $client->addCampaign("MyCampaign", "TheID");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	
	public function testUpdateCampaign(){
		$client = $this->client->Campaigns();
		$response = $client->updateCampaign(12345, "MyCampaign", "TheID");
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	
	public function testDeleteCampaign(){
		$client = $this->client->Campaigns();
		$response = $client->deleteCampaign(12345);
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	
	public function testGetCampaignEvents(){
		$client = $this->client->Campaigns();
		$response = $client->getCampaignEvents(12345, array());
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}

	public function testGetCampaignStats(){
		$client = $this->client->Campaigns();
		$response = $client->getCampaignStats(12345, array());
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	
	public function testGetCampaignClicks(){
		$client = $this->client->Campaigns();
		$response = $client->getCampaignClicks(12345, array());
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
	
	public function testGetCampaignOpens(){
		$client = $this->client->Campaigns();
		$response = $client->getCampaignOpens(12345, array());
		$httpCode = $response->http_response_code;
		$this->assertEquals(200, $httpCode);
	}
}