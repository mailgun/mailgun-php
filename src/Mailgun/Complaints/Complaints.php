<?PHP

/*
 *	SpamComplaints.php - Processing Spam Complaints.
*/
namespace Mailgun\Complaints;
	
class Complaints{

	private $httpBroker;
	private $workingDomain;
	private $endpointUrl;
	
	public function __construct($httpBroker){
		$this->httpBroker = $httpBroker;
		$this->endpointUrl = $this->httpBroker->returnWorkingDomain() . "/complaints";
	}
	
	public function addAddress($spamAddress){
		$postData = array("address" => $spamAddress);
		$response = $this->httpBroker->postRequest($this->endpointUrl, $postData);
		return $response;
	}
	
	public function deleteAddress($spamAddress){
		$requestUrl = $this->endpointUrl . "/" .  urlencode($spamAddress);
		$response = $this->httpBroker->deleteRequest($requestUrl);
		return $response;
	}
	
	public function getComplaint($spamAddress){
		$requestUrl = $this->endpointUrl . "/" .  urlencode($spamAddress);
		$response = $this->httpBroker->getRequest($requestUrl);
		return $response;
	}
	public function getComplaints($limit, $skip){
		$response = $this->httpBroker->getRequest($this->endpointUrl, array($limit, $skip));
		return $response;
	}

}