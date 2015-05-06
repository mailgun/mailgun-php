<?PHP

namespace Mailgun\Connection;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Post\PostBodyInterface;
use GuzzleHttp\Post\PostFile;
use GuzzleHttp\Query;
use Mailgun\Connection\Exceptions\GenericHTTPError;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\MissingRequiredParameters;
use Mailgun\Connection\Exceptions\MissingEndpoint;
use Mailgun\Constants\Api;
use Mailgun\Constants\ExceptionMessages;

/**
 * This class is a wrapper for the Guzzle (HTTP Client Library).
 */
class RestClient
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var Guzzle
     */
    protected $mgClient;

    /**
     * @param string $apiKey
     * @param string $apiEndpoint
     * @param string $apiVersion
     * @param bool   $ssl
     */
    public function __construct($apiKey, $apiEndpoint, $apiVersion, $ssl)
    {
        $this->apiKey = $apiKey;
        $this->mgClient = new Guzzle([
            'base_url'=>$this->generateEndpoint($apiEndpoint, $apiVersion, $ssl),
            'defaults'=>[
                'auth' => array(Api::API_USER, $this->apiKey),
                'exceptions' => false,
                'config' => ['curl' => [ CURLOPT_FORBID_REUSE => true ]],
                'headers' => [
                    'User-Agent' => Api::SDK_USER_AGENT.'/'.Api::SDK_VERSION,
                ],
            ],
        ]);
    }

    /**
     * @param string $endpointUrl
     * @param array  $postData
     * @param array  $files
     *
     * @return \stdClass
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     */
    public function post($endpointUrl, $postData = array(), $files = array())
    {
        $request = $this->mgClient->createRequest('POST', $endpointUrl, ['body' => $postData]);
        /** @var \GuzzleHttp\Post\PostBodyInterface $postBody */
        $postBody = $request->getBody();
        $postBody->setAggregator(Query::duplicateAggregator());

        $fields = ['message', 'attachment', 'inline'];
        foreach ($fields as $fieldName) {
            if (isset($files[$fieldName])) {
                if (is_array($files[$fieldName])) {
                    foreach ($files[$fieldName] as $file) {
                        $this->addFile($postBody, $fieldName, $file);
                    }
                } else {
                    $this->addFile($postBody, $fieldName, $files[$fieldName]);
                }
            }
        }

        $response = $this->mgClient->send($request);

        return $this->responseHandler($response);
    }

    /**
     * @param string $endpointUrl
     * @param array  $queryString
     *
     * @return \stdClass
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     */
    public function get($endpointUrl, $queryString = array())
    {
        $response = $this->mgClient->get($endpointUrl, ['query' => $queryString]);

        return $this->responseHandler($response);
    }

    /**
     * @param string $endpointUrl
     *
     * @return \stdClass
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     */
    public function delete($endpointUrl)
    {
        $response = $this->mgClient->delete($endpointUrl);

        return $this->responseHandler($response);
    }

    /**
     * @param string $endpointUrl
     * @param array  $putData
     *
     * @return \stdClass
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     */
    public function put($endpointUrl, $putData)
    {
        $request = $this->mgClient->createRequest('PUT', $endpointUrl, ['body' => $putData]);
        /** @var \GuzzleHttp\Post\PostBodyInterface $postBody */
        $postBody = $request->getBody();
        $postBody->setAggregator(Query::duplicateAggregator());

        $response = $this->mgClient->send($request);

        return $this->responseHandler($response);
    }

    /**
     * @param ResponseInterface $responseObj
     *
     * @return \stdClass
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     */
    public function responseHandler($responseObj)
    {
        $httpResponseCode = $responseObj->getStatusCode();
        if ($httpResponseCode === 200) {
            $data = (string) $responseObj->getBody();
            $jsonResponseData = json_decode($data, false);
            $result = new \stdClass();
            // return response data as json if possible, raw if not
            $result->http_response_body = $data && $jsonResponseData === null ? $data : $jsonResponseData;
        } elseif ($httpResponseCode == 400) {
            throw new MissingRequiredParameters(ExceptionMessages::EXCEPTION_MISSING_REQUIRED_PARAMETERS.$this->getResponseExceptionMessage($responseObj));
        } elseif ($httpResponseCode == 401) {
            throw new InvalidCredentials(ExceptionMessages::EXCEPTION_INVALID_CREDENTIALS);
        } elseif ($httpResponseCode == 404) {
            throw new MissingEndpoint(ExceptionMessages::EXCEPTION_MISSING_ENDPOINT.$this->getResponseExceptionMessage($responseObj));
        } else {
            throw new GenericHTTPError(ExceptionMessages::EXCEPTION_GENERIC_HTTP_ERROR, $httpResponseCode, $responseObj->getBody());
        }
        $result->http_response_code = $httpResponseCode;

        return $result;
    }

    /**
     * @param \Guzzle\Http\Message\Response $responseObj
     *
     * @return string
     */
    protected function getResponseExceptionMessage(\GuzzleHttp\Message\Response $responseObj)
    {
        $body = (string) $responseObj->getBody();
        $response = json_decode($body);
        if (json_last_error() == JSON_ERROR_NONE && isset($response->message)) {
            return " ".$response->message;
        }
    }

    /**
     * @param string $apiEndpoint
     * @param string $apiVersion
     * @param bool   $ssl
     *
     * @return string
     */
    private function generateEndpoint($apiEndpoint, $apiVersion, $ssl)
    {
        if (!$ssl) {
            return "http://".$apiEndpoint."/".$apiVersion."/";
        } else {
            return "https://".$apiEndpoint."/".$apiVersion."/";
        }
    }

    /**
     * Add a file to the postBody.
     *
     * @param PostBodyInterface $postBody
     * @param string            $fieldName
     * @param string|array      $filePath
     */
    private function addFile(PostBodyInterface $postBody, $fieldName, $filePath)
    {
        $filename = null;
        // Backward compatibility code
        if (is_array($filePath)) {
            $filename = $filePath['remoteName'];
            $filePath = $filePath['filePath'];
        }

        // Remove leading @ symbol
        if (strpos($filePath, '@') === 0) {
            $filePath = substr($filePath, 1);
        }

        $postBody->addFile(new PostFile($fieldName, fopen($filePath, 'r'), $filename));
    }
}
