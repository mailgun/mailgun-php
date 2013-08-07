<?PHP

/*
 *	Stats.php - Processing Stats.
*/
namespace Mailgun\Stats;
	
class Stats{

	private $restClient;
	private $workingDomain;
	private $endpointUrl;
	private $statsEndpointUrl;
	private $tagEndpointUrl;
	
	public function __construct($restClient){
		$this->restClient = $restClient;
		$this->statsEndpointUrl = $this->restClient->returnWorkingDomain() . "/stats";
		$this->tagEndpointUrl = $this->restClient->returnWorkingDomain() . "/tags";
	}
	
	public function deleteTag($tag){
		$requestUrl = $this->tagEndpointUrl . "/" .  urlencode($tag);
		$response = $this->restClient->deleteRequest($requestUrl);
		return $response;
	}
	
	public function getStats($filterParams = array()){
		$response = $this->restClient->getRequest($this->statsEndpointUrl, $filterParams);
		return $response;
	}
}