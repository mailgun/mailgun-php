<?PHP
namespace Mailgun\Tests\Connection;

use Mailgun\Common\Client;

class ConnectionTest extends \Mailgun\Tests\MailgunTestCase{
	public function setUp(){ 
		//Do we need to setup anything? Not sure yet. Leaving this function here until I need it!
	}
	public function testNewClientConnection(){
		$client = new Client("key-3ax6xnjp29jd6fds4gc373sgvjxteol0", "samples.mailgun.org", false);	
		$result = $client->validateCredentials();
		$this->assertTrue($result);
	}
	/**
	* @depends testNewClientConnection
	*/
	public function sendSimpleTestMessage(){
		$client = new Client("key-3ax6xnjp29jd6fds4gc373sgvjxteol0", "samples.mailgun.org", false);	
		$result = $client->sendMessage(array("from" => "Excited User <me@samples.mailgun.org>", "to" => "travis@tswientek.com", "subject" => "Hello", "Text" => "PHP Unit Test Success!", "o:testmode" => false));
		$results = $result->getResponseCode();
		$this->assertEquals("20", $results);
	}
}

?>