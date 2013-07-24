<?PHP
namespace Mailgun\Tests\BatchMessage;

use Mailgun\Connection\Client;
use Mailgun\Messages\BatchMessage;

class BatchMessageTest extends \Mailgun\Tests\MailgunTestCase{

	private $client;

	public function setUp(){ 
		$this->client = new Client(DEFAULT_MG_API_KEY, DEFAULT_MG_DOMAIN, false);	
	}
	public function testBlankInstantiation(){
		$message = new BatchMessage($this->client, true);
		$this->assertTrue(is_array($message->getMessage()));
	}
	public function testAddBatchRecipient(){
		$message = new BatchMessage($this->client, true);
		$message->addBatchRecipient("test@samples.mailgun.org", array("first" => "Test", "last" => "User"));
		$messageObj= $message->getMessage();
		$this->assertEquals(array("to" => array("Test User <test@samples.mailgun.org>")), $messageObj);
	}
	public function testAddMultipleBatchRecipients(){
		$message = new BatchMessage($this->client, true);
		for($i=0; $i<100; $i++){
			$message->addBatchRecipient("$i@samples.mailgun.org", array("first" => "Test", "last" => "User $i"));
		}
		$messageObj= $message->getMessage();
		$this->assertEquals(100, count($messageObj["to"]));
	}
	public function testMaximumBatchSize(){
		$message = new BatchMessage($this->client, true);
		for($i=0; $i<1001; $i++){
			$message->addBatchRecipient("$i@samples.mailgun.org", array("first" => "Test", "last" => "User $i"));
		}
		$messageObj= $message->getMessage();
		$this->assertEquals(1, count($messageObj["to"]));
	}
	public function testResetOnEndBatchMessage(){
		$message = new BatchMessage($this->client, true);
		$message->addBatchRecipient("test-user@samples.mailgun.org", array("first" => "Test", "last" => "User"));
		$message->endBatchMessage();
		$messageObj= $message->getMessage();
		$this->assertTrue(true, empty($messageObj));
	}
}
?>






































































