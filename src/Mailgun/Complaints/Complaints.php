<?PHP

/*
 *	SpamComplaints.php - Processing Spam Complaints.
*/
namespace Mailgun\Complaints;
	
class Complaints{

	private $restClient;
	private $workingDomain;
	private $endpointUrl;
	
	public function __construct($restClient){
		$this->restClient = $restClient;
		$this->endpointUrl = $this->restClient->returnWorkingDomain() . "/complaints";
	}
	
	public function addAddress($spamAddress){
		$postData = array("address" => $spamAddress);
		$response = $this->restClient->postRequest($this->endpointUrl, $postData);
		return $response;
	}
	
	public function deleteAddress($spamAddress){
		$requestUrl = $this->endpointUrl . "/" .  urlencode($spamAddress);
		$response = $this->restClient->deleteRequest($requestUrl);
		return $response;
	}
	
	public function getComplaint($spamAddress){
		$requestUrl = $this->endpointUrl . "/" .  urlencode($spamAddress);
		$response = $this->restClient->getRequest($requestUrl);
		return $response;
	}
	public function getComplaints($limit, $skip){
		$response = $this->restClient->getRequest($this->endpointUrl, array($limit, $skip));
		return $response;
	}

}