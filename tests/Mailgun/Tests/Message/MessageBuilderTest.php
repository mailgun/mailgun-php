<?PHP
namespace Mailgun\Tests\Message;

use Mailgun\Tests\MailgunClientTest;

class MessageBuilderTest extends \Mailgun\Tests\MailgunTestCase{
	private $client;

	public function setUp(){ 
		$this->client = new MailgunClientTest("My-Super-Awesome-API-Key", "samples.mailgun.org", false);	
	}
	public function testBlankInstantiation(){
		$message = $this->client->Messages()->MessageBuilder();
		$this->assertTrue(is_array($message->getMessage()));
	}
	
	public function testAddToRecipient(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->addToRecipient("test@samples.mailgun.org", array("first" => "Test", "last" => "User"));
		$messageObj = $message->getMessage();
		$this->assertEquals(array("to" => array("Test User <test@samples.mailgun.org>")), $messageObj);
	}
	public function testAddCcRecipient(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->addCcRecipient("test@samples.mailgun.org", array("first" => "Test", "last" => "User"));
		$messageObj = $message->getMessage();
		$this->assertEquals(array("cc" => array("Test User <test@samples.mailgun.org>")), $messageObj);
	}
	public function testAddBccRecipient(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->addBccRecipient("test@samples.mailgun.org", array("first" => "Test", "last" => "User"));
		$messageObj = $message->getMessage();
		$this->assertEquals(array("bcc" => array("Test User <test@samples.mailgun.org>")), $messageObj);
	}
	public function testSetFromAddress(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->setFromAddress("test@samples.mailgun.org", array("first" => "Test", "last" => "User"));
		$messageObj = $message->getMessage();
		$this->assertEquals(array("from" => "Test User <test@samples.mailgun.org>"), $messageObj);
	}
	public function testSetSubject(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->setSubject("Test Subject");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("subject" => "Test Subject"), $messageObj);
	}
	public function testAddCustomHeader(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->addCustomHeader("My-Header", "123");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("h:My-Header" => array("123")), $messageObj);
	}
	public function testSetTextBody(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->setTextBody("This is the text body!");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("text" => "This is the text body!"), $messageObj);
	}
	public function testSetHtmlBody(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->setHtmlBody("<html><body>This is an awesome email</body></html>");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("html" => "<html><body>This is an awesome email</body></html>"), $messageObj);
	}
	public function testAddAttachments(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->addAttachment("@../TestAssets/mailgun_icon.png");
		$message->addAttachment("@../TestAssets/rackspace_logo.png");
		$messageObj = $message->getFiles();
		$this->assertEquals(array("attachment" => array("@../TestAssets/mailgun_icon.png", "@../TestAssets/rackspace_logo.png")), $messageObj);
	}
	public function testAddInlineImages(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->addInlineImage("@../TestAssets/mailgun_icon.png");
		$message->addInlineImage("@../TestAssets/rackspace_logo.png");
		$messageObj = $message->getFiles();
		$this->assertEquals(array("inline" => array("@../TestAssets/mailgun_icon.png", "@../TestAssets/rackspace_logo.png")), $messageObj);
	}
	public function testsetTestMode(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->setTestMode(true);
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:testmode" => "yes"), $messageObj);
		$message->setTestMode(false);
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:testmode" => "no"), $messageObj);
		$message->setTestMode("yes");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:testmode" => "yes"), $messageObj);
		$message->setTestMode("no");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:testmode" => "no"), $messageObj);
	}
	public function addCampaignId(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->addCampaignId("ABC123");
		$message->addCampaignId("XYZ987");
		$message->addCampaignId("TUV456");
		$message->addCampaignId("NONO123");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:campaign" => array("ABC123", "XYZ987", "TUV456")), $messageObj);
	}
	public function testSetDkim(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->setDkim(true);
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:dkim" => "yes"), $messageObj);
		$message->setDkim(false);
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:dkim" => "no"), $messageObj);
		$message->setDkim("yes");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:dkim" => "yes"), $messageObj);
		$message->setDkim("no");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:dkim" => "no"), $messageObj);
	}
	public function testSetClickTracking(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->setClickTracking(true);
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:tracking-clicks" => "yes"), $messageObj);
		$message->setClickTracking(false);
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:tracking-clicks" => "no"), $messageObj);
		$message->setClickTracking("yes");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:tracking-clicks" => "yes"), $messageObj);
		$message->setClickTracking("no");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:tracking-clicks" => "no"), $messageObj);
	}
	public function testSetOpenTracking(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->setOpenTracking(true);
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:tracking-opens" => "yes"), $messageObj);
		$message->setOpenTracking(false);
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:tracking-opens" => "no"), $messageObj);
		$message->setOpenTracking("yes");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:tracking-opens" => "yes"), $messageObj);
		$message->setOpenTracking("no");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:tracking-opens" => "no"), $messageObj);
	}
	public function testSetDeliveryTime(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->setDeliveryTime("January 15, 2014 8:00AM", "CST");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:deliverytime" => "Wed, 15 Jan 2014 08:00:00 -0600"), $messageObj);
		$message->setDeliveryTime("January 15, 2014 8:00AM", "UTC");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:deliverytime" => "Wed, 15 Jan 2014 08:00:00 +0000"), $messageObj);
		$message->setDeliveryTime("1/15/2014 13:50:01", "CDT");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:deliverytime" => "Wed, 15 Jan 2014 13:50:01 -0600"), $messageObj);
		$message->setDeliveryTime("first saturday of July 2013 8:00AM", "CDT");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("o:deliverytime" => "Sat, 06 Jul 2013 08:00:00 -0500"), $messageObj);
	}
	public function testAddCustomData(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->addCustomData("My-Super-Awesome-Data", array("What" => "Mailgun Rocks!"));
		$messageObj = $message->getMessage();
		$this->assertEquals(array("v:My-Super-Awesome-Data" => "{\"What\":\"Mailgun Rocks!\"}"), $messageObj);
	}
	public function testAddCustomOption(){
		$message = $this->client->Messages()->MessageBuilder();
		$message->addCustomOption("my-option", "yes");
		$message->addCustomOption("o:my-other-option", "no");
		$messageObj = $message->getMessage();
		$this->assertEquals(array("options" => array("o:my-option" => array("yes"), "o:my-other-option" => array("no"))), $messageObj);
	}
}

?>