<?PHP

require('Mailgun/Common/Messages.php');

class MessagesClassTests extends PHPUnit_Framework_TestCase{
	public function setUp(){ }
	public function tearDown(){ }
	public function testPropertySetGetMethods(){
		
		$message = new Mailgun\Common\Message();
		//Adds a recipient
		$this->assertTrue($message->addToRecipient("travis@trstx.com", "travis swientek") == true);
	
		//Checks if recipient was added to array.
		$this->assertContains('travis swientek <travis@trstx.com>', $message->getToRecipients());
		
		//Add a CC recipient
		$this->assertTrue($message->addCcRecipient("travis@trstx.com", "travis swientek") == true);
	
		//Checks if recipient was added to array.
		$this->assertContains('travis swientek <travis@trstx.com>', $message->getCcRecipients());
		
		//Add a BCC recipient
		$this->assertTrue($message->addBccRecipient("travis@trstx.com", "travis swientek") == true);
	
		//Checks if recipient was added to array.
		$this->assertContains('travis swientek <travis@trstx.com>', $message->getBccRecipients());
		
		//Add a From address
		$this->assertTrue($message->setFromAddress("travis@trstx.com", "travis swientek") == true);
	
		//Checks if recipient was added to array.
		$this->assertContains('travis swientek <travis@trstx.com>', $message->getFromAddress());
		
		//Set a subject for the email
		$this->assertTrue($message->setSubject("This is my subject!") == true);
	
		//Checks if subject is added
		$this->assertEquals('This is my subject!', $message->getSubject());
		
		//Fail to set a subject by calling an empty method
		$this->assertTrue($message->setSubject() == false);

		//If failing to set a subject, set the subject to "". 		
		$this->assertEquals('', $message->getSubject());
		
		//Set a Text body for the email
		$this->assertTrue($message->setTextBody("This is my email text.") == true);
	
		//Checks if Text body is added
		$this->assertEquals("This is my email text.", $message->getTextBody());
		
		//Set an HTML body for the email
		$this->assertTrue($message->setHTMLBody("<html><head></head><body>This is my HTML email.</body></html>") == true);
	
		//Checks if an HTML body is added
		$this->assertEquals("<html><head></head><body>This is my HTML email.</body></html>", $message->getHTMLBody());
		
		//Set a Campaign ID
		$this->assertTrue($message->setCampaignId("My-Super-Awesome-Campaign") == true);
	
		//Checks if Campaign ID exists
		$this->assertContains("My-Super-Awesome-Campaign", $message->getCampaignId());
		
		

	}
}

?>