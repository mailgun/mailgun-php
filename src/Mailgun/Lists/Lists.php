<?PHP

/*
 *	Lists.php - Processing LIsts.
*/
namespace Mailgun\Lists;
	
class Lists{

	private $httpBroker;
	private $workingDomain;
	private $endpointUrl;

	
	public function __construct($httpBroker){
		$this->httpBroker = $httpBroker;
		$this->endpointUrl = $this->httpBroker->returnWorkingDomain() . "/lists";
	}
	
	public function getLists($limit, $skip){
		$response = $this->httpBroker->getRequest($this->endpointUrl, array($limit, $skip));
		return $response;
	}
}

?>