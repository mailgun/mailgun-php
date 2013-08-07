<?PHP

/*
 *	Routes.php - Processing Routes.
*/
namespace Mailgun\Routes;

use Mailgun\Routes\Exceptions\InvalidParameter;
	
class Routes{

	private $restClient;
	private $workingDomain;
	private $endpointUrl;

	public function __construct($restClient){
		$this->restClient = $restClient;
		$this->endpointUrl = "routes";
	}
	
	public function getRoutes($limit, $skip){
		$response = $this->restClient->getRequest($this->endpointUrl, array($limit, $skip));
		return $response;
	}
	
	public function getRoute($routeId){
		$updatedUrl = $this->endpointUrl . "/" . $routeId;
		$response = $this->restClient->getRequest($updatedUrl);
		return $response;
	}
	
	public function addRoute($priority, $description, $expression, $action){
		
		if(!is_int($priority) || $priority < 0){
			throw new InvalidParameter("The priority is not a positive integer.");
		}
		
		if(!isset($description)){
			throw new InvalidParameter("The description seems to be missing.");
		}
		
		if(!isset($expression)){
			throw new InvalidParameter("The expression seems to be missing.");
		}	
		if(!isset($action)){
			throw new InvalidParameter("The action seems to be missing.");
		}
		
		$postData = array('priority' => $priority, 'description' => $description, 'expression' => $expression, 'action' => $action);
	
		$response = $this->restClient->postRequest($this->endpointUrl, $postData);
		return $response;
	}

	public function updateRoute($routeId, $priority, $description, $expression, $action){
		if(!is_int($priority) || $priority < 0){
			throw new InvalidParameter("The priority is not a positive integer.");
		}
		
		if(!isset($description)){
			throw new InvalidParameter("The description seems to be missing.");
		}
		
		if(!isset($expression)){
			throw new InvalidParameter("The expression seems to be missing.");
		}	
		if(!isset($action)){
			throw new InvalidParameter("The action seems to be missing.");
		}
		
		$postData = array('priority' => $priority, 'description' => $description, 'expression' => $expression, 'action' => $action);	
		
		$updatedUrl = $this->endpointUrl . "/" . $routeId;
		
		$response = $this->restClient->putRequest($updatedUrl, $postData);
		return $response;
	}
	
	public function deleteRoute($routeId){
		$updatedUrl = $this->endpointUrl . "/" . $routeId;
		$response = $this->restClient->deleteRequest($updatedUrl);
		return $response;
	}
}

?>