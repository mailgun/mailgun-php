<?PHP

/*
 *	Logs.php - Processing Logs.
*/
namespace Mailgun\Logs;
	
class Logs{

	private $restClient;
	private $workingDomain;
	private $endpointUrl;

	
	public function __construct($restClient){
		$this->restClient = $restClient;
		$this->endpointUrl = $this->restClient->returnWorkingDomain() . "/log";
	}
	
	public function getLogs($limit, $skip){
		$response = $this->restClient->getRequest($this->endpointUrl, array($limit, $skip));
		return $response;
	}
}

?>