<?PHP

/*
 *	Logs.php - Processing Logs.
*/
namespace Mailgun\Logs;
	
class Logs{

	private $httpBroker;
	private $workingDomain;
	private $endpointUrl;

	
	public function __construct($httpBroker){
		$this->httpBroker = $httpBroker;
		$this->endpointUrl = $this->httpBroker->returnWorkingDomain() . "/log";
	}
	
	public function getLogs($limit, $skip){
		$response = $this->httpBroker->getRequest($this->endpointUrl, array($limit, $skip));
		return $response;
	}
}