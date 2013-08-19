<?PHP
namespace Mailgun\Tests\Lists;

use Mailgun\Tests\MailgunTest;

class OptInHandler extends \Mailgun\Tests\MailgunTestCase{

	private $client;
	private $sampleDomain = "samples.mailgun.org";
	private $optInHandler;

	public function setUp(){ 
		$this->client = new MailgunTest("My-Super-Awesome-API-Key");
		$this->optInHandler = $this->client->OptInHandler();
	}
	
	public function testReturnOfGenerateHash(){
		$generatedHash = $this->optInHandler->generateHash('mytestlist@example.com', 'mysupersecretappid', 'testrecipient@example.com');
		$knownHash = "eyJzIjoiOGM2NmVmYzYwNzhmNGVkYjFkZGJiY2RhM2M2MmMzMTQiLCJsIjoibXl0ZXN0bGlzdEBleGFtcGxlLmNvbSIsInIiOiJ0ZXN0cmVjaXBpZW50QGV4YW1wbGUuY29tIn0%3D";
		$this->assertEquals($generatedHash, $knownHash);
	}
	
	public function testGoodHash(){
		$validation = $this->optInHandler->validateHash('mysupersecretappid', 'eyJzIjoiOGM2NmVmYzYwNzhmNGVkYjFkZGJiY2RhM2M2MmMzMTQiLCJsIjoibXl0ZXN0bGlzdEBleGFtcGxlLmNvbSIsInIiOiJ0ZXN0cmVjaXBpZW50QGV4YW1wbGUuY29tIn0%3D');
		$this->assertArrayHasKey('recipientAddress', $validation);
		$this->assertArrayHasKey('mailingList', $validation);
	}
	public function testBadHash(){
		$validation = $this->optInHandler->validateHash('mybadsecretappid', 'eyJzIjoiOGM2NmVmYzYwNzhmNGVkYjFkZGJiY2RhM2M2MmMzMTQiLCJsIjoibXl0ZXN0bGlzdEBleGFtcGxlLmNvbSIsInIiOiJ0ZXN0cmVjaXBpZW50QGV4YW1wbGUuY29tIn0%3D');
		$this->assertFalse($validation);
	}
}
?>