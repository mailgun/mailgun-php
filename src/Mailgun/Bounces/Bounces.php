<?PHP

/*
 *	Bounces.php - Processing Bounces.
*/
namespace Mailgun\Bounces;
	
class Bounces{

	private $restClient;
	private $workingDomain;
	private $endpointUrl;
	
	public function __construct($restClient){
		$this->restClient = $restClient;
		$this->endpointUrl = $this->restClient->returnWorkingDomain() . "/bounces";
	}
	
	public function addAddress($bounceAddress, $bounceCode, $bounceError = null){
		if(isset($bounceError)){
			$postData = array("address" => $bounceAddress, "code" => $bounceCode, "error" => $bounceError);
		}
		else{
			$postData = array("address" => $bounceAddress, "code" => $bounceCode);
		}
		$response = $this->restClient->postRequest($this->endpointUrl, $postData);
		return $response;
	}
	
	public function deleteAddress($bounceAddress){
		$requestUrl = $this->endpointUrl . "/" .  urlencode($bounceAddress);
		$response = $this->restClient->deleteRequest($requestUrl);
		return $response;
	}
	
	public function getBounce($bounceAddress){
		$requestUrl = $this->endpointUrl . "/" .  urlencode($bounceAddress);
		$response = $this->restClient->getRequest($requestUrl);
		return $response;
	}
	public function getBounces($limit, $skip){
		$response = $this->restClient->getRequest($this->endpointUrl, array($limit, $skip));
		return $response;
	}

}