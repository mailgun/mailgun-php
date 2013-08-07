<?PHP

/*
 *	Address.php - Validate Addresses.
*/
namespace Mailgun\Address;
	
class Address{

	private $restClient;
	private $workingDomain;
	private $endpointUrl;
	
	public function __construct($restClient){
		$this->restClient = $restClient;
		$this->endpointUrl = "address";
	}
	
	public function getValidate($address){
		$updatedUrl = $this->endpointUrl . "/validate";
		$getData = array('address' => $address);
		$response = $this->restClient->getRequest($updatedUrl, $getData);
		return $response;
	}
}

?>