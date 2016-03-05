<?PHP

namespace Mailgun;

use Http\Client\HttpClient;
use Mailgun\Constants\ExceptionMessages;
use Mailgun\Messages\Exceptions;
use Mailgun\Connection\RestClient;
use Mailgun\Messages\BatchMessage;
use Mailgun\Lists\OptInHandler;
use Mailgun\Messages\MessageBuilder;

/**
 * This class is the base class for the Mailgun SDK.
 * See the official documentation (link below) for usage instructions.
 *
 * @link https://github.com/mailgun/mailgun-php/blob/master/README.md
 */
class Mailgun{

    /**
     * @var RestClient
     */
    protected $restClient;

    /**
     * @var null|string
     */
    protected $apiKey;

    /**
     * @param string|null $apiKey
     * @param HttpClient $httpClient
     * @param string $apiEndpoint
     */
    public function __construct(
        $apiKey = null,
        HttpClient $httpClient = null,
        $apiEndpoint = 'api.mailgun.net'
    ) {
        $this->apiKey = $apiKey;
        $this->restClient = new RestClient($apiKey, $apiEndpoint, $httpClient);
    }

    /**
     *  This function allows the sending of a fully formed message OR a custom
     *  MIME string. If sending MIME, the string must be passed in to the 3rd
     *  position of the function call.
     *
     * @param string $workingDomain
     * @param array $postData
     * @param array $postFiles
     * @throws Exceptions\MissingRequiredMIMEParameters
     */
    public function sendMessage($workingDomain, $postData, $postFiles = array()){
        if(is_array($postFiles)){
            return $this->post("$workingDomain/messages", $postData, $postFiles);
        }
        else if(is_string($postFiles)){

            $tempFile = tempnam(sys_get_temp_dir(), "MG_TMP_MIME");
            $fileHandle = fopen($tempFile, "w");
            fwrite($fileHandle, $postFiles);

            $result = $this->post("$workingDomain/messages.mime", $postData, array("message" => $tempFile));
            fclose($fileHandle);
            unlink($tempFile);
            return $result;
        }
        else{
            throw new Exceptions\MissingRequiredMIMEParameters(ExceptionMessages::EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS);
        }
    }

    /**
     * This function checks the signature in a POST request to see if it is
     * authentic.
     *
     * Pass an array of parameters.  If you pass nothing, $_POST will be
     * used instead.
     *
     * If this function returns FALSE, you must not process the request.
     * You should reject the request with status code 403 Forbidden.
     *
     * @param array|null $postData
     * @return bool
     */
    public function verifyWebhookSignature($postData = NULL) {
        if(is_null($postData)) {
            $postData = $_POST;
        }
        $hmac = hash_hmac('sha256', "{$postData["timestamp"]}{$postData["token"]}", $this->apiKey);
        $sig = $postData['signature'];
        if(function_exists('hash_equals')) {
            // hash_equals is constant time, but will not be introduced until PHP 5.6
            return hash_equals($hmac, $sig);
        }
        else {
            return ($hmac == $sig);
        }
    }

    /**
     * @param string $endpointUrl
     * @param array $postData
     * @param array $files
     * @return \stdClass
     */
    public function post($endpointUrl, $postData = array(), $files = array()){
        return $this->restClient->post($endpointUrl, $postData, $files);
    }

    /**
     * @param string $endpointUrl
     * @param array $queryString
     * @return \stdClass
     */
    public function get($endpointUrl, $queryString = array()){
        return $this->restClient->get($endpointUrl, $queryString);
    }

    /**
     * @param string $endpointUrl
     * @return \stdClass
     */
    public function delete($endpointUrl){
        return $this->restClient->delete($endpointUrl);
    }

    /**
     * @param string $endpointUrl
     * @param array $putData
     * @return \stdClass
     */
    public function put($endpointUrl, $putData){
        return $this->restClient->put($endpointUrl, $putData);
    }

    /**
     * @param string $apiVersion
     *
     * @return Mailgun
     */
    public function setApiVersion($apiVersion)
    {
        $this->restClient->setApiVersion($apiVersion);

        return $this;
    }

    /**
     * @param boolean $sslEnabled
     *
     * @return Mailgun
     */
    public function setSslEnabled($sslEnabled)
    {
        $this->restClient->setSslEnabled($sslEnabled);

        return $this;
    }

    /**
     * @return MessageBuilder
     */
    public function MessageBuilder(){
        return new MessageBuilder();
    }

    /**
     * @return OptInHandler
     */
    public function OptInHandler(){
        return new OptInHandler();
    }

    /**
     * @param string $workingDomain
     * @param bool $autoSend
     * @return BatchMessage
     */
    public function BatchMessage($workingDomain, $autoSend = true){
        return new BatchMessage($this->restClient, $workingDomain, $autoSend);
    }
}
