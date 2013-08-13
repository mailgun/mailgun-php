<?PHP
namespace Mailgun\Tests\Messages;

use Mailgun\Tests\MailgunTest;

class BatchMessageTest extends \Mailgun\Tests\MailgunTestCase{

	private $client;
	private $sampleDomain = "samples.mailgun.org";

	public function setUp(){ 
		$this->client = new MailgunTest("My-Super-Awesome-API-Key");
	}
	public function testBlankInstantiation(){
		$message = $this->client->BatchMessage($this->sampleDomain);
		$this->assertTrue(is_array($message->getMessage()));
	}
	public function testaddToRecipient(){
		$message = $this->client->BatchMessage($this->sampleDomain);
		$message->addToRecipient("test@samples.mailgun.org", array("first" => "Test", "last" => "User"));
		$messageObj= $message->getMessage();
		$this->assertEquals(array("to" => array("'Test User' <test@samples.mailgun.org>")), $messageObj);
	}
	public function testAddMultipleBatchRecipients(){
		$message = $this->client->BatchMessage($this->sampleDomain);
		for($i=0; $i<100; $i++){
			$message->addToRecipient("$i@samples.mailgun.org", array("first" => "Test", "last" => "User $i"));
		}
		$messageObj= $message->getMessage();
		$this->assertEquals(100, count($messageObj["to"]));
	}
	public function testMaximumBatchSize(){
		$message = $this->client->BatchMessage($this->sampleDomain);
		$message->setFromAddress("samples@mailgun.org", array("first" => "Test", "last" => "User"));
		$message->setSubject("This is the subject of the message!");
		$message->setTextBody("This is the text body of the message!");
		for($i=0; $i<1001; $i++){
			$message->addToRecipient("$i@samples.mailgun.org", array("first" => "Test", "last" => "User $i"));
		}
		$messageObj= $message->getMessage();
		$this->assertEquals(1, count($messageObj["to"]));
	}
	public function testResetOnEndBatchMessage(){
		$message = $this->client->BatchMessage($this->sampleDomain);
		$message->addToRecipient("test-user@samples.mailgun.org", array("first" => "Test", "last" => "User"));
		$message->setFromAddress("samples@mailgun.org", array("first" => "Test", "last" => "User"));
		$message->setSubject("This is the subject of the message!");
		$message->setTextBody("This is the text body of the message!");
		$message->finalize();
		$messageObj= $message->getMessage();
		$this->assertTrue(true, empty($messageObj));
	}
}
?>






































































