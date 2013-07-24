<?PHP

/*
 *	Unsubscribe.php - Processing unsubscribes.
*/
namespace Mailgun\Unsubscribes;
	
class Unsubscribe{

	private $client;
	
	public function __construct($client){
		$this->client = $client;
	}
	
	public function addUnsubscribe($address, $tag = NULL){
		if(isset($tag)){
			$data = array("address" => $address, "tag" => $tag);
		}
		else{
			$data = array("address" => $address, "tag" => "*");
		}
		$response = $this->client->postUnsubscribe($data);
		return $response;
	}
	
	public function deleteUnsubscribe($address){
		$response = $this->client->deleteUnsubscribe($address);
		return $response;
	}
	
	public function getUnsubscribe($address){
		
	}

}