<?PHP

namespace Mailgun\Connection;

use Guzzle\Http\Client as Guzzle;
use Mailgun\MailgunClient;

use Mailgun\Connection\Exceptions\GenericHTTPError;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\NoDomainsConfigured;
use Mailgun\Connection\Exceptions\MissingRequiredParameters;
use Mailgun\Connection\Exceptions\MissingEndpoint;

/* 
   This class is a wrapper for the Guzzle (HTTP Client Library). 
*/

class RestClient{

	private $apiKey;
	protected $mgClient;
	
	public function __construct($apiKey, $apiEndpoint, $apiVersion){	
		$this->apiKey = $apiKey;
		$this->mgClient = new Guzzle('https://' . $apiEndpoint . '/' . $apiVersion . '/');
		$this->mgClient->setDefaultOption('curl.options', array('CURLOPT_FORBID_REUSE' => true));
		$this->mgClient->setDefaultOption('auth', array (API_USER, $this->apiKey));	
		$this->mgClient->setDefaultOption('exceptions', false);
		$this->mgClient->setUserAgent(SDK_USER_AGENT . '/' . SDK_VERSION);
	}
	
	public function post($endpointUrl, $postData = array(), $files = array()){
		$request = $this->mgClient->post($endpointUrl, array(), $postData);
		
		if(isset($files["message"])){
			foreach($files as $message){
				$request->addPostFile("message", $message);
			}
		}
		if(isset($files["attachment"])){
			foreach($files["attachment"] as $attachment){
				$request->addPostFile("attachment", $attachment);
			}
		}
		if(isset($files["inline"])){
			foreach($files["inline"] as $attachment){
				$request->addPostFile("inline", $attachment);
			}
		}

		$response = $request->send();
		return $this->responseHandler($response);
	}
	
	public function get($endpointUrl, $queryString = array()){
		$request = $this->mgClient->get($endpointUrl);
		if(isset($queryString)){
			foreach($queryString as $key=>$value){
				$request->getQuery()->set($key, $value);
			}			
		}
		$response = $request->send();
		return $this->responseHandler($response);
	}
	
	public function delete($endpointUrl){
		$request = $this->mgClient->delete($endpointUrl);
		$response = $request->send();
		return $this->responseHandler($response);	
	}
	
	public function put($endpointUrl, $putData){
		$request = $this->mgClient->put($endpointUrl, array(), $putData);
		$response = $request->send();
		return $this->responseHandler($response);
	}
	
	public function responseHandler($responseObj){
		$httpResponseCode = $responseObj->getStatusCode();
		if($httpResponseCode === 200){
			$jsonResponseData = json_decode($responseObj->getBody(), false);
			$result = new \stdClass();
			$result->http_response_body = $jsonResponseData;
		}
		elseif($httpResponseCode == 400){
			throw new MissingRequiredParameters(EXCEPTION_MISSING_REQUIRED_PARAMETERS);
		}
		elseif($httpResponseCode == 401){
			throw new InvalidCredentials(EXCEPTION_INVALID_CREDENTIALS);
		}
		elseif($httpResponseCode == 404){
			throw new MissingEndpoint(EXCEPTION_MISSING_ENDPOINT);
		}
		else{
			throw new GenericHTTPError(EXCEPTION_GENERIC_HTTP_ERROR);
		}
		$result->http_response_code = $httpResponseCode;
		return $result;
	}
}

?>