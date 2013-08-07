<?PHP

namespace Mailgun\Tests;

use Mailgun\MailgunClient;
use Mailgun\Tests\Connection\TestBroker;

class MailgunClientTest extends MailgunClient
{
	protected $debug;
	protected $restClient;
	
	public function __construct($apiKey, $domain, $debug = false){
		$this->restClient = new TestBroker($apiKey, $domain, $debug);
		return true;
	}
} 


?>