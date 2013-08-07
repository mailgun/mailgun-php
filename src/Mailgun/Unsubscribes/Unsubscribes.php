<?PHP

/*
 *	Unsubscribe.php - Processing unsubscribes.
*/
namespace Mailgun\Unsubscribes;
	
class Unsubscribes{

	private $restClient;
	private $workingDomain;
	private $endpointUrl;
	
	public function __construct($restClient){
		$this->restClient = $restClient;
		$this->endpointUrl = $this->restClient->returnWorkingDomain() . "/unsubscribes";
	}
	
	public function addAddress($unsubAddress, $unsubTag = NULL){
		if(isset($unsubTag)){
			$postData = array("address" => $unsubAddress, "tag" => $unsubTag);
		}
		else{
			$postData = array("address" => $unsubAddress, "tag" => "*");
		}
		$response = $this->restClient->postRequest($this->endpointUrl, $postData);
		return $response;
	}
	
	public function deleteAddress($unsubAddress){
		$requestUrl = $this->endpointUrl . "/" .  urlencode($unsubAddress);
		$response = $this->restClient->deleteRequest($requestUrl);
		return $response;
	}
	
	public function getUnsubscribe($unsubAddress){
		$requestUrl = $this->endpointUrl . "/" .  urlencode($unsubAddress);
		$response = $this->restClient->getRequest($requestUrl);
		return $response;
	}
	public function getUnsubscribes($limit, $skip){
		$response = $this->restClient->getRequest($this->endpointUrl, array($limit, $skip));
		return $response;
	}

}