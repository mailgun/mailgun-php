<?PHP
namespace Mailgun\Tests\Message;

use Mailgun\Common\Message;

class MessageTest extends \Mailgun\Tests\MailgunTestCase{
	public function setUp(){ 
		//Do we need to setup anything? Not sure yet. Leaving this function here until I need it!
	}
	public function testBlankInstantiation(){
		$message = new Message();
		$this->assertTrue(is_array($message->getMessage()));
	}
}

?>