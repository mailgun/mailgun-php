<?PHP

/*
 *	Stats.php - Processing Stats.
*/
namespace Mailgun\Stats;
	
class Stats{

	private $httpBroker;
	private $workingDomain;
	private $endpointUrl;
	private $statsEndpointUrl;
	private $tagEndpointUrl;
	
	public function __construct($httpBroker){
		$this->httpBroker = $httpBroker;
		$this->statsEndpointUrl = $this->httpBroker->returnWorkingDomain() . "/stats";
		$this->tagEndpointUrl = $this->httpBroker->returnWorkingDomain() . "/tags";
	}
	
	public function deleteTag($tag){
		$requestUrl = $this->tagEndpointUrl . "/" .  urlencode($tag);
		$response = $this->httpBroker->deleteRequest($requestUrl);
		return $response;
	}
	
	public function getStats($limit, $skip){
		$response = $this->httpBroker->getRequest($this->statsEndpointUrl, array($limit, $skip, $event, $start-date));
		return $response;
	}
}