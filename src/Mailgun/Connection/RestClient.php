<?PHP

namespace Mailgun\Connection;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mailgun\Connection\Exceptions\GenericHTTPError;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\MissingRequiredParameters;
use Mailgun\Connection\Exceptions\MissingEndpoint;
use Mailgun\Constants\Api;
use Mailgun\Constants\ExceptionMessages;
use Psr\Http\Message\ResponseInterface;

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
            'base_uri'=>$this->generateEndpoint($apiEndpoint, $apiVersion, $ssl),
            'auth' => array(Api::API_USER, $this->apiKey),
            'exceptions' => false,
            'config' => ['curl' => [ CURLOPT_FORBID_REUSE => true ]],
            'headers' => [
                'User-Agent' => Api::SDK_USER_AGENT.'/'.Api::SDK_VERSION,
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
        $request = new Request('post', $endpointUrl);
        $postFiles = [];

        $fields = ['message', 'attachment', 'inline'];
        foreach ($fields as $fieldName) {
            if (isset($files[$fieldName])) {
                if (is_array($files[$fieldName])) {
                    foreach ($files[$fieldName] as $file) {
                        $postFiles[] = $this->addFile($fieldName, $file);
                    }
                } else {
                    $postFiles[] = $this->addFile($fieldName, $files[$fieldName]);
                }
            }
        }

        $postDataMultipart = [];
        foreach($postData AS $key => $value)
        {
            if (is_array($value))
            {
                foreach($value AS $subValue)
                {
                    $postDataMultipart[] = [
                        'name' => $key,
                        'contents' => $subValue
                    ];
                }
            }
            else
            {
                $postDataMultipart[] = [
                    'name' => $key,
                    'contents' => $value
                ];
            }
        }

        $response = $this->mgClient->send($request, [
            'multipart' => array_merge($postDataMultipart, $postFiles)
        ]);
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
        $response = $this->mgClient->request('PUT', $endpointUrl, ['body' => $putData]);
        return $this->responseHandler($response);
    }

    /**
     * @param Response $responseObj
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
     * @param Response $responseObj
     *
     * @return string
     */
    protected function getResponseExceptionMessage(Response $responseObj)
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
    protected function generateEndpoint($apiEndpoint, $apiVersion, $ssl)
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
     * @param \GuzzleHttp\Psr7\Stream            $postBody
     * @param string            $fieldName
     * @param string|array      $filePath
     */
    protected function addFile($fieldName, $filePath)
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

        return [
            'name' => $fieldName,
            'contents' => fopen($filePath, 'r'),
            'filename' => $filename
        ];
    }
}
