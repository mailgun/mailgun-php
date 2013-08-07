<?PHP

/*
 *	Lists.php - Processing LIsts.
*/
namespace Mailgun\Lists;
	
class Lists{

	private $restClient;
	private $workingDomain;
	private $endpointUrl;

	
	public function __construct($restClient){
		$this->restClient = $restClient;
		$this->endpointUrl = "lists";
		
	}
	
	public function getLists($limit, $skip){
		$response = $this->restClient->getRequest($this->endpointUrl, array($limit, $skip));
		return $response;
	}
	public function getList($listAddress){
		$updatedUrl = $this->endpointUrl . "/" . $listAddress;
		$response = $this->restClient->getRequest($updatedUrl);
		return $response;
	}
	public function addList($listAddress, $name, $description, $access_level){
		$postData = array('address' => $listAddress, 'name' => $name, 'description' => $description, 'access_level' => $access_level);
		$response = $this->restClient->postRequest($this->endpointUrl, $postData);
		return $response;
	}
	public function updateList($listAddress, $name, $description, $access_level){
		$updatedUrl = $this->endpointUrl . "/" . $listAddress;
		$postData = array('address' => $listAddress, 'name' => $name, 'description' => $description, 'access_level' => $access_level);
		$response = $this->restClient->putRequest($updatedUrl, $postData);
		return $response;
	}
	public function deleteList($listAddress){
		$updatedUrl = $this->endpointUrl . "/" . $listAddress;
		$response = $this->restClient->deleteRequest($updatedUrl);
		return $response;
	}
	public function getListMembers($listAddress, $filterParams = array()){
		$updatedUrl = $this->endpointUrl . "/" . $listAddress . "/members";
		$response = $this->restClient->getRequest($updatedUrl, $filterParams);
		return $response;
	}
	public function getListMember($listAddress, $memberAddress){
		$updatedUrl = $this->endpointUrl . "/" . $listAddress . "/members/" . $memberAddress;
		$response = $this->restClient->getRequest($updatedUrl);
		return $response;
	}
	public function addListMember($listAddress, $memberAddress, $name, $vars, $subscribed = true, $upsert = true){
		$updatedUrl = $this->endpointUrl . "/" . $listAddress . "/members";
		$postData = array('address' => $memberAddress, 'name' => $name, 'vars' => $vars, 'subscribed' => $subscribed, 'upsert' => $upsert);
		$response = $this->restClient->postRequest($updatedUrl, $postData);
		return $response;
	}
	public function updateListMember($listAddress, $memberAddress, $name, $vars, $subscribed = true){
		$updatedUrl = $this->endpointUrl . "/" . $listAddress . "/members/" . $memberAddress;
		$postData = array('address' => $memberAddress, 'name' => $name, 'vars' => $vars, 'subscribed' => $subscribed);
		$response = $this->restClient->putRequest($updatedUrl, $postData);
		return $response;
	}
	public function deleteListMember($listAddress, $memberAddress){
		$updatedUrl = $this->endpointUrl . "/" . $listAddress . "/members/" . $memberAddress;
		$response = $this->restClient->deleteRequest($updatedUrl);
		return $response;
	}
	public function getListStats($listAddress){
		$updatedUrl = $this->endpointUrl . "/" . $listAddress . "/stats";
		$response = $this->restClient->getRequest($updatedUrl);
		return $response;
	}
}

?>