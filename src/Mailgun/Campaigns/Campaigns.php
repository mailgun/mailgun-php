<?PHP

/*
 *	Campaigns.php - Processing Campaigns.
*/
namespace Mailgun\Campaigns;
	
class Campaigns{

	private $httpBroker;
	private $workingDomain;
	private $endpointUrl;

	
	public function __construct($httpBroker){
		$this->httpBroker = $httpBroker;
		$this->endpointUrl = $this->httpBroker->returnWorkingDomain() . "/campaigns";
	}
	
	public function getCampaigns($limit, $skip){
		$response = $this->httpBroker->getRequest($this->endpointUrl, array($limit, $skip));
		return $response;
	}
	
	public function getCampaign($campaignId){
		$updatedUrl = $this->endpointUrl . "/" . $campaignId;
		$response = $this->httpBroker->getRequest($updatedUrl);
		return $response;
	}
	
	public function addCampaign($name, $id){
		if(isset($id) && strlen($id) > 64){
			throw new InvalidParameter("The message ID is too long. Limit is 64 characters.");
		}
		$postData = array('name' => $name, 'id' => $id);
		$response = $this->httpBroker->postRequest($this->endpointUrl, $postData);
		return $response;
	}
	
	public function updateCampaign($campaignId, $name, $id){
		if(isset($id) && strlen($id) > 64){
			throw new InvalidParameter("The message ID is too long. Limit is 64 characters.");
		}
		$updatedUrl = $this->endpointUrl . "/" . $campaignId;
		$postData = array('name' => $name, 'id' => $id);
		$response = $this->httpBroker->putRequest($updatedUrl, $postData);
		return $response;
	}
	
	public function deleteCampaign($campaignId){
		$updatedUrl = $this->endpointUrl . "/" . $campaignId;
		$response = $this->httpBroker->deleteRequest($updatedUrl);
		return $response;
	}
	
	public function getCampaignEvents($campaignId, $filterParams = array()){
		$updatedUrl = $this->endpointUrl . "/" . $campaignId . "/events";
		$response = $this->httpBroker->getRequest($updatedUrl, $filterParams);
		return $response;
	}
	
	public function getCampaignStats($campaignId, $filterParams = array()){
		$updatedUrl = $this->endpointUrl . "/" . $campaignId . "/stats";
		$response = $this->httpBroker->getRequest($updatedUrl, $filterParams);
		return $response;
	}
	
	public function getCampaignClicks($campaignId, $filterParams = array()){
		$updatedUrl = $this->endpointUrl . "/" . $campaignId . "/clicks";
		$response = $this->httpBroker->getRequest($updatedUrl, $filterParams);
		return $response;
	}

	public function getCampaignOpens($campaignId, $filterParams = array()){
		$updatedUrl = $this->endpointUrl . "/" . $campaignId . "/opens";
		$response = $this->httpBroker->getRequest($updatedUrl, $filterParams);
		return $response;
	}

	public function getCampaignUnsubscribes($campaignId, $filterParams = array()){
		$updatedUrl = $this->endpointUrl . "/" . $campaignId . "/unsubscribes";
		$response = $this->httpBroker->getRequest($updatedUrl, $filterParams);
		return $response;
	}
	
	public function getCampaignComplaints($campaignId, $filterParams = array()){
		$updatedUrl = $this->endpointUrl . "/" . $campaignId . "/clicks";
		$response = $this->httpBroker->getRequest($updatedUrl, $filterParams);
		return $response;
	}
}

?>