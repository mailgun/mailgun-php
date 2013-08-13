<?PHP

namespace Mailgun\Tests;

use Mailgun\Mailgun;
use Mailgun\Tests\Connection\TestBroker;

class MailgunTest extends Mailgun
{
	protected $debug;
	protected $restClient;
	
	public function __construct($apiKey = null, $apiEndpoint = "api.mailgun.net", $apiVersion = "v2"){
		$this->restClient = new TestBroker($apiKey, $apiEndpoint, $apiVersion);
	}
} 


?>