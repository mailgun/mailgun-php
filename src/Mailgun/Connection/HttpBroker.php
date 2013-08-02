<?PHP

namespace Mailgun\Connection;
	
require dirname(__DIR__) . '/Globals.php';

use Guzzle\Http\Client as Guzzle;
use Mailgun\MailgunClient;

use Mailgun\Connection\Exceptions\GenericHTTPError;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\NoDomainsConfigured;
use Mailgun\Connection\Exceptions\MissingRequiredMIMEParameters;

class HttpBroker{

	private $apiKey;
	protected $workingDomain;
	protected $debugMode;
	protected $mgClient;
	
	public function __construct($apiKey, $workingDomain, $debugMode = false){
	
		$this->apiKey = $apiKey;
		$this->workingDomain = $workingDomain;
		$this->debugMode = $debugMode;
		$this->mgClient = new Guzzle('https://' . API_ENDPOINT . '/' . API_VERSION . '/');
		$this->mgClient->setDefaultOption('curl.options', array('CURLOPT_FORBID_REUSE' => true));
		$this->mgClient->setDefaultOption('auth', array (API_USER, $this->apiKey));	
		$this->mgClient->setDefaultOption('exceptions', true);
		$this->mgClient->setUserAgent(SDK_USER_AGENT . '/' . SDK_VERSION);
	}
	
	public function postRequest($endpointUrl, $postData = array(), $files = array()){
		$request = $this->mgClient->post($endpointUrl, array(), $postData);
		
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
	
	public function getRequest($endpointUrl, $queryString = array()){
		$request = $this->mgClient->get($endpointUrl);
		foreach($queryString as $key=>$value){
			$request->getQuery()->set($key, $value);
		}
		$response = $request->send();
		return $this->responseHandler($response);
	}
	
	public function deleteRequest($endpointUrl){
		$request = $this->mgClient->delete($endpointUrl);
		$response = $request->send();
		return $this->responseHandler($response);	
	}
	
	public function putRequest($endpointUrl, $putData){
		$request = $this->mgClient->put($endpointUrl, array(), $putData);
		$response = $request->send();
		return $this->responseHandler($response);
	}
	
	public function responseHandler($responseObj){
		$httpResponeCode = $responseObj->getStatusCode();
		if($httpResponeCode === 200){
			$jsonResponseData = $responseObj->json();
			foreach ($jsonResponseData as $key => $value){
			    $result->http_response_body->$key = $value;
			}
		}
		elseif($httpStatusCode == 400){
			throw new MissingRequiredMIMEParameters(EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
		}
		elseif($httpStatusCode == 401){
			throw new InvalidCredentials(EXCEPTION_INVALID_CREDENTIALS);
		}
		elseif($httpStatusCode == 401){
			throw new GenericHTTPError(EXCEPTION_INVALID_CREDENTIALS);
		}
		elseif($httpStatusCode == 404){
			throw new MissingEndpoint(EXCEPTION_MISSING_ENDPOINT);
		}
		else{
			throw new GenericHTTPError(EXCEPTION_GENERIC_HTTP_ERROR);
			return false;
		}
		$result->http_response_code = $httpResponeCode;
		return $result;
	}
	
	public function returnWorkingDomain(){
		return $this->workingDomain;
	}
}

?>