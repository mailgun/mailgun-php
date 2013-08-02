<?PHP

/*
 *	Address.php - Validate Addresses.
*/
namespace Mailgun\Address;
	
class Address{

	private $httpBroker;
	private $workingDomain;
	private $endpointUrl;
	
	public function __construct($httpBroker){
		$this->httpBroker = $httpBroker;
		$this->endpointUrl = $this->httpBroker->returnWorkingDomain() . "/address";
	}
	
	public function validateAddress($address){
		$updatedUrl = $this->endpointUrl . "/validate";
		$getData = array('address' => $address);
		$response = $this->httpBroker->getRequest($updatedUrl, $getData);
		return $response;
	}
}

?>