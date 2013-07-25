<?PHP

/*
 *	Bounces.php - Processing Bounces.
*/
namespace Mailgun\Bounces;
	
class Bounces{

	private $httpBroker;
	private $workingDomain;
	private $endpointUrl;
	
	public function __construct($httpBroker){
		$this->httpBroker = $httpBroker;
		$this->endpointUrl = $this->httpBroker->returnWorkingDomain() . "/bounces";
	}
	
	public function addAddress($bounceAddress, $bounceCode, $bounceError = null){
		if(isset($bounceError)){
			$postData = array("address" => $bounceAddress, "code" => $bounceCode, "error" => $bounceError);
		}
		else{
			$postData = array("address" => $bounceAddress, "code" => $bounceCode);
		}
		$response = $this->httpBroker->postRequest($this->endpointUrl, $postData);
		return $response;
	}
	
	public function deleteAddress($bounceAddress){
		$requestUrl = $this->endpointUrl . "/" .  urlencode($bounceAddress);
		$response = $this->httpBroker->deleteRequest($requestUrl);
		return $response;
	}
	
	public function getBounce($bounceAddress){
		$requestUrl = $this->endpointUrl . "/" .  urlencode($bounceAddress);
		$response = $this->httpBroker->getRequest($requestUrl);
		return $response;
	}
	public function getBounces($limit, $skip){
		$response = $this->httpBroker->getRequest($this->endpointUrl, array($limit, $skip));
		return $response;
	}

}