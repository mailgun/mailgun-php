<?PHP

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\HttpClient;
use Mailgun\Connection\RestClient;
use Mailgun\Constants\ExceptionMessages;
use Mailgun\Lists\OptInHandler;
use Mailgun\Messages\BatchMessage;
use Mailgun\Messages\Exceptions;
use Mailgun\Messages\MessageBuilder;
use Mailgun\Deserializer\ModelDeserializer;
use Mailgun\Deserializer\ResponseDeserializer;

/**
 * This class is the base class for the Mailgun SDK.
 */
class Mailgun
{
    /**
     * @var RestClient
     *
     * @depracated Will be removed in 3.0
     */
    protected $restClient;

    /**
     * @var null|string
     */
    protected $apiKey;

    /**
     * @var HttpMethodsClient
     */
    private $httpClient;

    /**
     * @var ResponseDeserializer
     */
    private $deserializer;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * @param string|null                 $apiKey
     * @param HttpClient|null             $httpClient
     * @param string                      $apiEndpoint
     * @param ResponseDeserializer|null   $deserializer
     * @param HttpClientConfigurator|null $clientConfigurator
     * @param RequestBuilder|null         $requestBuilder
     */
    public function __construct(
        $apiKey = null,
        HttpClient $httpClient = null, /* Deprecated, will be removed in 3.0 */
        $apiEndpoint = 'api.mailgun.net', /* Deprecated, will be removed in 3.0 */
        ResponseDeserializer $deserializer = null,
        HttpClientConfigurator $clientConfigurator = null,
        RequestBuilder $requestBuilder = null
    ) {
        $this->apiKey = $apiKey;
        $this->restClient = new RestClient($apiKey, $apiEndpoint, $httpClient);

        if (null === $clientConfigurator) {
            $clientConfigurator = new HttpClientConfigurator();

            /*
             * To be backward compatible
             */
            if ($apiEndpoint !== 'api.mailgun.net') {
                $clientConfigurator->setEndpoint($apiEndpoint);
            }
            if ($httpClient !== null) {
                $clientConfigurator->setHttpClient($httpClient);
            }
        }

        $clientConfigurator->setApiKey($apiKey);

        $this->httpClient = $clientConfigurator->createConfiguredClient();
        $this->requestBuilder = $requestBuilder ?: new RequestBuilder();
        $this->deserializer = $deserializer ?: new ModelDeserializer();
    }

    /**
     *  This function allows the sending of a fully formed message OR a custom
     *  MIME string. If sending MIME, the string must be passed in to the 3rd
     *  position of the function call.
     *
     * @param string $workingDomain
     * @param array  $postData
     * @param array  $postFiles
     *
     * @throws Exceptions\MissingRequiredMIMEParameters
     *
     * @return \stdClass
     */
    public function sendMessage($workingDomain, $postData, $postFiles = [])
    {
        if (is_array($postFiles)) {
            return $this->post("$workingDomain/messages", $postData, $postFiles);
        } elseif (is_string($postFiles)) {
            $tempFile = tempnam(sys_get_temp_dir(), 'MG_TMP_MIME');
            $fileHandle = fopen($tempFile, 'w');
            fwrite($fileHandle, $postFiles);

            $result = $this->post("$workingDomain/messages.mime", $postData, ['message' => $tempFile]);
            fclose($fileHandle);
            unlink($tempFile);

            return $result;
        } else {
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
     *
     * @return bool
     */
    public function verifyWebhookSignature($postData = null)
    {
        if ($postData === null) {
            $postData = $_POST;
        }
        if (!isset($postData['timestamp']) || !isset($postData['token']) || !isset($postData['signature'])) {
            return false;
        }
        $hmac = hash_hmac('sha256', "{$postData['timestamp']}{$postData['token']}", $this->apiKey);
        $sig = $postData['signature'];
        if (function_exists('hash_equals')) {
            // hash_equals is constant time, but will not be introduced until PHP 5.6
            return hash_equals($hmac, $sig);
        } else {
            return $hmac === $sig;
        }
    }

    /**
     * @param string $endpointUrl
     * @param array  $postData
     * @param array  $files
     *
     * @return \stdClass
     */
    public function post($endpointUrl, $postData = [], $files = [])
    {
        return $this->restClient->post($endpointUrl, $postData, $files);
    }

    /**
     * @param string $endpointUrl
     * @param array  $queryString
     *
     * @return \stdClass
     */
    public function get($endpointUrl, $queryString = [])
    {
        return $this->restClient->get($endpointUrl, $queryString);
    }

    /**
     * @param string $url
     *
     * @return \stdClass
     */
    public function getAttachment($url)
    {
        return $this->restClient->getAttachment($url);
    }

    /**
     * @param string $endpointUrl
     *
     * @return \stdClass
     */
    public function delete($endpointUrl)
    {
        return $this->restClient->delete($endpointUrl);
    }

    /**
     * @param string $endpointUrl
     * @param array  $putData
     *
     * @return \stdClass
     */
    public function put($endpointUrl, $putData)
    {
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
     * @param bool $sslEnabled
     *
     * @return Mailgun
     *
     * @deprecated This will be removed in 3.0. Mailgun does not support non-secure connections to their API.
     */
    public function setSslEnabled($sslEnabled)
    {
        $this->restClient->setSslEnabled($sslEnabled);

        return $this;
    }

    /**
     * @return MessageBuilder
     */
    public function MessageBuilder()
    {
        return new MessageBuilder();
    }

    /**
     * @return OptInHandler
     */
    public function OptInHandler()
    {
        return new OptInHandler();
    }

    /**
     * @param string $workingDomain
     * @param bool   $autoSend
     *
     * @return BatchMessage
     */
    public function BatchMessage($workingDomain, $autoSend = true)
    {
        return new BatchMessage($this->restClient, $workingDomain, $autoSend);
    }

    /**
     * @return Api\Stats
     */
    public function stats()
    {
        return new Api\Stats($this->httpClient, $this->requestBuilder, $this->deserializer);
    }

    /**
     * @return Api\Domain
     */
    public function domains()
    {
        return new Api\Domain($this->httpClient, $this->requestBuilder, $this->deserializer);
    }

    /**
     * @return Api\Tag
     */
    public function tag()
    {
        return new Api\Tag($this->httpClient, $this->requestBuilder, $this->deserializer);
    }

    /**
     * @return Api\Event
     */
    public function events()
    {
        return new Api\Event($this->httpClient, $this->requestBuilder, $this->deserializer);
    }

    /**
     * @return Api\Routes
     */
    public function routes()
    {
        return new Api\Routes($this->httpClient, $this->requestBuilder, $this->deserializer);
    }

    /**
     * @return Api\Webhook
     */
    public function webhooks()
    {
        return new Api\Webhook($this->httpClient, $this->requestBuilder, $this->deserializer);
    }

    /**
     * @return Api\Message
     */
    public function messages()
    {
        return new Api\Message($this->httpClient, $this->requestBuilder, $this->deserializer);
    }

    /**
     * @return Api\Suppressions
     */
    public function suppressions()
    {
        return new Api\Suppressions($this->httpClient, $this->requestBuilder, $this->deserializer);
    }
}
