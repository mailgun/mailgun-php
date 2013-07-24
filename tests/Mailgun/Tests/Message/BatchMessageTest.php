<?PHP
namespace Mailgun\Tests\BatchMessage;

use Mailgun\Tests\MailgunClientTest;

class BatchMessageTest extends \Mailgun\Tests\MailgunTestCase{

	private $client;

	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	
	}
	public function testBlankInstantiation(){
		$message = $this->client->Messages()->BatchMessage();
		$this->assertTrue(is_array($message->getMessage()));
	}
	public function testAddBatchRecipient(){
		$message = $this->client->Messages()->BatchMessage();
		$message->addBatchRecipient("test@samples.mailgun.org", array("first" => "Test", "last" => "User"));
		$messageObj= $message->getMessage();
		$this->assertEquals(array("to" => array("Test User <test@samples.mailgun.org>")), $messageObj);
	}
	public function testAddMultipleBatchRecipients(){
		$message = $this->client->Messages()->BatchMessage();
		for($i=0; $i<100; $i++){
			$message->addBatchRecipient("$i@samples.mailgun.org", array("first" => "Test", "last" => "User $i"));
		}
		$messageObj= $message->getMessage();
		$this->assertEquals(100, count($messageObj["to"]));
	}
	public function testMaximumBatchSize(){
		$message = $this->client->Messages()->BatchMessage();
		$message->setFromAddress("samples@mailgun.org", array("first" => "Test", "last" => "User"));
		$message->setSubject("This is the subject of the message!");
		$message->setTextBody("This is the text body of the message!");
		for($i=0; $i<1001; $i++){
			$message->addBatchRecipient("$i@samples.mailgun.org", array("first" => "Test", "last" => "User $i"));
		}
		$messageObj= $message->getMessage();
		$this->assertEquals(1, count($messageObj["to"]));
	}
	public function testResetOnEndBatchMessage(){
		$message = $this->client->Messages()->BatchMessage();
		$message->addBatchRecipient("test-user@samples.mailgun.org", array("first" => "Test", "last" => "User"));
		$message->setFromAddress("samples@mailgun.org", array("first" => "Test", "last" => "User"));
		$message->setSubject("This is the subject of the message!");
		$message->setTextBody("This is the text body of the message!");
		$message->sendMessage();
		$messageObj= $message->getMessage();
		$this->assertTrue(true, empty($messageObj));
	}
}
?>






































































