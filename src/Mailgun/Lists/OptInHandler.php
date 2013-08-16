<?PHP

namespace Mailgun\Lists;

use Mailgun\Messages\Expcetions\InvalidParameter;
use Mailgun\Messages\Exceptions\TooManyParameters;
use Mailgun\Messages\Expcetions\InvalidParameterType;

/* 
   This class is used for creating a unique hash for 
   mailing list subscription double-opt in requests.
*/

class OptInHandler{

	function __construct(){
		
	}
	
	public function generateHash($mailingList, $secretAppId, $recipientAddress){
		$concatStrings = $secretAppId . "" . $recipientAddress;		
		return urlencode(base64_encode(json_encode(array('s' => hash('md5', $concatStrings), 'l' => $mailingList, 'r' => $recipientAddress))));
	}

	public function validateHash($secretAppId, $uniqueHash){
		$urlParameters = json_decode(base64_decode(urldecode($uniqueHash)));
		$concatStrings = $secretAppId . "" . $urlParameters->r;	
		
		if($urlParameters->s == hash('md5', $concatStrings)){
			$returnArray = array('recipientAddress' => $urlParameters->r, 'mailingList' => $urlParameters->l);
				return $returnArray;
		}
		return false;
	}
}