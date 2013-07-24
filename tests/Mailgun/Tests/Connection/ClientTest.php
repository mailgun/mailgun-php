<?PHP
namespace Mailgun\Tests\Connection;

use Mailgun\Connection\Client;
use Mailgun\Connection\Exceptions\NoDomainsConfigured;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\MissingRequiredMIMEParameters;
use Mailgun\Connection\Exceptions\GenericHTTPError;

class ClientTest extends \Mailgun\Tests\MailgunTestCase{

	private $client;

	public function setUp(){ 
		$this->client = new Client(\DEFAULT_MG_API_KEY, \DEFAULT_MG_DOMAIN, false);	
		$path = "../../Mock/messages";
		$this->setMockResponse($this->client, $path);
	}
	public function testNewClientConnection(){	
		$result = $this->client->validateCredentials();
		$this->assertTrue($result);
	}

	public function testSendSimpleTestMessage(){
		$result = $this->client->sendMessage(array("from" => "Excited User <me@samples.mailgun.org>", "to" => "travis@tswientek.com", "subject" => "Hello", "text" => "PHP Unit Test Success!", "o:testmode" => true));
		$status = $result->getStatusCode();
		$this->assertEquals("200", $status);
	}
	
	/**
     * @expectedException Mailgun\Connection\Exceptions\InvalidCredentials
     */
	
	public function testBadCredentialsException(){
		$throwAway = new Client("key-this-is-not-valid", \DEFAULT_MG_DOMAIN, false);		
	}
	
	/**
     * @expectedException Mailgun\Connection\Exceptions\MissingRequiredMIMEParameters
     */
     
	public function testRequiredMIMEParametersException(){
		$this->client->sendMessage(array("from" => "Excited User <me@samples.mailgun.org>", "subject" => "Hello", "text" => "PHP Unit Test Success!", "o:testmode" => true));
	}
	
}

?>