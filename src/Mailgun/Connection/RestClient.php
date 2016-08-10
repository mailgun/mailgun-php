<?PHP

namespace Mailgun\Connection;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Mailgun\Connection\Exceptions\GenericHTTPError;
use Mailgun\Connection\Exceptions\InvalidCredentials;
use Mailgun\Connection\Exceptions\MissingEndpoint;
use Mailgun\Connection\Exceptions\MissingRequiredParameters;
use Mailgun\Constants\Api;
use Mailgun\Constants\ExceptionMessages;
use Psr\Http\Message\ResponseInterface;

/**
 * This class is a wrapper for the HTTP client.
 */
class RestClient
{
    /**
     * Your API key.
     *
     * @var string
     */
    private $apiKey;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $apiHost;

    /**
     * The version of the API to use.
     *
     * @var string
     */
    protected $apiVersion = 'v2';

    /**
     * If we should use SSL or not.
     *
     * @var bool
     */
    protected $sslEnabled = true;

    /**
     * @param string     $apiKey
     * @param string     $apiHost
     * @param HttpClient $httpClient
     */
    public function __construct($apiKey, $apiHost, HttpClient $httpClient = null)
    {
        $this->apiKey = $apiKey;
        $this->apiHost = $apiHost;
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param mixed  $body
     * @param array  $files
     * @param array  $headers
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    protected function send($method, $uri, $body = null, $files = [], array $headers = [])
    {
        $headers['User-Agent'] = Api::SDK_USER_AGENT.'/'.Api::SDK_VERSION;
        $headers['Authorization'] = 'Basic '.base64_encode(sprintf('%s:%s', Api::API_USER, $this->apiKey));

        if (!empty($files)) {
            $builder = new MultipartStreamBuilder();
            foreach ($files as $file) {
                $builder->addResource($file['name'], $file['contents'], $file);
            }
            $body = $builder->build();
            $headers['Content-Type'] = 'multipart/form-data; boundary='.$builder->getBoundary();
        } elseif (is_array($body)) {
            $body = http_build_query($body);
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        $request = MessageFactoryDiscovery::find()->createRequest($method, $this->getApiUrl($uri), $headers, $body);
        $response = $this->getHttpClient()->sendRequest($request);

        return $this->responseHandler($response);
    }

    /**
     * @param string $endpointUrl
     * @param array  $postData
     * @param array  $files
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    public function post($endpointUrl, array $postData = [], $files = [])
    {
        $postFiles = [];

        $fields = ['message', 'attachment', 'inline'];
        foreach ($fields as $fieldName) {
            if (isset($files[$fieldName])) {
                if (is_array($files[$fieldName])) {
                    $fileIndex = 0;
                    foreach ($files[$fieldName] as $file) {
                        $postFiles[] = $this->prepareFile($fieldName, $file, $fileIndex);
                        $fileIndex++;
                    }
                } else {
                    $postFiles[] = $this->prepareFile($fieldName, $files[$fieldName]);
                }
            }
        }

        $postDataMultipart = [];
        foreach ($postData as $key => $value) {
            if (is_array($value)) {
                $index = 0;
                foreach ($value as $subValue) {
                    $postDataMultipart[] = [
                        'name'     => sprintf('%s[%d]', $key, $index++),
                        'contents' => $subValue,
                    ];
                }
            } else {
                $postDataMultipart[] = [
                    'name'     => $key,
                    'contents' => $value,
                ];
            }
        }

        return $this->send('POST', $endpointUrl, [], array_merge($postDataMultipart, $postFiles));
    }

    /**
     * @param string $endpointUrl
     * @param array  $queryString
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    public function get($endpointUrl, $queryString = [])
    {
        return $this->send('GET', $endpointUrl.'?'.http_build_query($queryString));
    }

    /**
     * @param string $endpointUrl
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    public function delete($endpointUrl)
    {
        return $this->send('DELETE', $endpointUrl);
    }

    /**
     * @param string $endpointUrl
     * @param mixed  $putData
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    public function put($endpointUrl, $putData)
    {
        return $this->send('PUT', $endpointUrl, $putData);
    }

    /**
     * @param ResponseInterface $responseObj
     *
     * @throws GenericHTTPError
     * @throws InvalidCredentials
     * @throws MissingEndpoint
     * @throws MissingRequiredParameters
     *
     * @return \stdClass
     */
    public function responseHandler(ResponseInterface $responseObj)
    {
        $httpResponseCode = (int) $responseObj->getStatusCode();

        switch ($httpResponseCode) {
        case 200:
            $data = (string) $responseObj->getBody();
            $jsonResponseData = json_decode($data, false);
            $result = new \stdClass();
            // return response data as json if possible, raw if not
            $result->http_response_body = $data && $jsonResponseData === null ? $data : $jsonResponseData;
            $result->http_response_code = $httpResponseCode;

            return $result;
        case 400:
            throw new MissingRequiredParameters(ExceptionMessages::EXCEPTION_MISSING_REQUIRED_PARAMETERS.$this->getResponseExceptionMessage($responseObj));
        case 401:
            throw new InvalidCredentials(ExceptionMessages::EXCEPTION_INVALID_CREDENTIALS);
        case 404:
            throw new MissingEndpoint(ExceptionMessages::EXCEPTION_MISSING_ENDPOINT.$this->getResponseExceptionMessage($responseObj));
        default:
            throw new GenericHTTPError(ExceptionMessages::EXCEPTION_GENERIC_HTTP_ERROR, $httpResponseCode, $responseObj->getBody());
        }
    }

    /**
     * @param ResponseInterface $responseObj
     *
     * @return string
     */
    protected function getResponseExceptionMessage(ResponseInterface $responseObj)
    {
        $body = (string) $responseObj->getBody();
        $response = json_decode($body);
        if (json_last_error() == JSON_ERROR_NONE && isset($response->message)) {
            return ' '.$response->message;
        }

        return '';
    }

    /**
     * Prepare a file for the postBody.
     *
     * @param string       $fieldName
     * @param string|array $filePath
     * @param integer      $fileIndex
     *
     * @return array
     */
    protected function prepareFile($fieldName, $filePath, $fileIndex=0)
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

        // Add index for multiple file support
        $fieldName .= '[' . $fileIndex . ']';

        return [
            'name'     => $fieldName,
            'contents' => fopen($filePath, 'r'),
            'filename' => $filename,
        ];
    }

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = HttpClientDiscovery::find();
        }

        return $this->httpClient;
    }

    /**
     * @param $uri
     *
     * @return string
     */
    private function getApiUrl($uri)
    {
        return $this->generateEndpoint($this->apiHost, $this->apiVersion, $this->sslEnabled).$uri;
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
        return ($ssl ? 'https://' : 'http://').$apiEndpoint.'/'.$apiVersion.'/';
    }

    /**
     * @param string $apiVersion
     *
     * @return RestClient
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * @param bool $sslEnabled
     *
     * @return RestClient
     */
    public function setSslEnabled($sslEnabled)
    {
        $this->sslEnabled = $sslEnabled;

        return $this;
    }
}
