<?PHP

/*
 *	Unsubscribe.php - Processing unsubscribes.
*/
namespace Mailgun\Unsubscribes;
	
class Unsubscribes{

	private $httpBroker;
	private $workingDomain;
	private $endpointUrl;
	
	public function __construct($httpBroker){
		$this->httpBroker = $httpBroker;
		$this->endpointUrl = $this->httpBroker->returnWorkingDomain() . "/unsubscribes";
	}
	
	public function addAddress($unsubAddress, $unsubTag = NULL){
		if(isset($unsubTag)){
			$postData = array("address" => $unsubAddress, "tag" => $unsubTag);
		}
		else{
			$postData = array("address" => $unsubAddress, "tag" => "*");
		}
		$response = $this->httpBroker->postRequest($this->endpointUrl, $postData);
		return $response;
	}
	
	public function deleteAddress($unsubAddress){
		$requestUrl = $this->endpointUrl . "/" .  urlencode($unsubAddress);
		$response = $this->httpBroker->deleteRequest($requestUrl);
		return $response;
	}
	
	public function getAddress($unsubAddress){
		$requestUrl = $this->endpointUrl . "/" .  urlencode($unsubAddress);
		$response = $this->httpBroker->getRequest($requestUrl);
		return $response;
	}
	public function getAddresses($limit, $skip){
		$response = $this->httpBroker->getRequest($this->endpointUrl, array($limit, $skip));
		return $response;
	}

}