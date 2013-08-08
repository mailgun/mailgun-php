<?PHP

namespace Mailgun\Tests;

use Mailgun\Mailgun;
use Mailgun\Tests\Connection\TestBroker;

class MailgunTest extends Mailgun
{
	protected $debug;
	protected $restClient;
	
	public function __construct($apiKey = null, $apiEndpoint = "api.mailgun.net"){
		$this->restClient = new TestBroker($apiKey, $apiEndpoint);
		return true;
	}
} 


?>