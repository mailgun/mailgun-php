<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\HttpClient;
use Mailgun\Api\MailingList;
use Mailgun\Connection\RestClient;
use Mailgun\Constants\ExceptionMessages;
use Mailgun\HttpClient\Plugin\History;
use Mailgun\Lists\OptInHandler;
use Mailgun\Messages\BatchMessage;
use Mailgun\Messages\Exceptions;
use Mailgun\Messages\MessageBuilder;
use Mailgun\Hydrator\ModelHydrator;
use Mailgun\Hydrator\Hydrator;
use Psr\Http\Message\ResponseInterface;

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
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * This is a object that holds the last response from the API.
     *
     * @var History
     */
    private $responseHistory = null;

    /**
     * @param string|null         $apiKey
     * @param HttpClient|null     $httpClient
     * @param string              $apiEndpoint
     * @param Hydrator|null       $hydrator
     * @param RequestBuilder|null $requestBuilder
     *
     * @internal Use Mailgun::configure or Mailgun::create instead.
     */
    public function __construct(
        $apiKey = null, /* Deprecated, will be removed in 3.0 */
        HttpClient $httpClient = null,
        $apiEndpoint = 'api.mailgun.net', /* Deprecated, will be removed in 3.0 */
        Hydrator $hydrator = null,
        RequestBuilder $requestBuilder = null
    ) {
        $this->apiKey = $apiKey;
        $this->restClient = new RestClient($apiKey, $apiEndpoint, $httpClient);

        $this->httpClient = $httpClient;
        $this->requestBuilder = $requestBuilder ?: new RequestBuilder();
        $this->hydrator = $hydrator ?: new ModelHydrator();
    }

    /**
     * @param HttpClientConfigurator $configurator
     * @param Hydrator|null          $hydrator
     * @param RequestBuilder|null    $requestBuilder
     *
     * @return Mailgun
     */
    public static function configure(
        HttpClientConfigurator $configurator,
        Hydrator $hydrator = null,
        RequestBuilder $requestBuilder = null
    ) {
        $httpClient = $configurator->createConfiguredClient();

        return new self($configurator->getApiKey(), $httpClient, 'api.mailgun.net', $hydrator, $requestBuilder);
    }

    /**
     * @param string $apiKey
     * @param string $endpoint URL to mailgun servers
     *
     * @return Mailgun
     */
    public static function create($apiKey, $endpoint = 'https://api.mailgun.net')
    {
        $httpClientConfigurator = (new HttpClientConfigurator())
            ->setApiKey($apiKey)
            ->setEndpoint($endpoint);

        return self::configure($httpClientConfigurator);
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
     *
     * @deprecated Use Mailgun->messages()->send() instead. Will be removed in 3.0
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
     *
     * @deprecated Use Mailgun->webhook() instead. Will be removed in 3.0
     */
    public function verifyWebhookSignature($postData = null)
    {
        if (null === $postData) {
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
     * @return ResponseInterface|null
     */
    public function getLastResponse()
    {
        return $this->responseHistory->getLastResponse();
    }

    /**
     * @param string $endpointUrl
     * @param array  $postData
     * @param array  $files
     *
     * @return \stdClass
     *
     * @deprecated Will be removed in 3.0
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
     *
     * @deprecated Will be removed in 3.0
     */
    public function get($endpointUrl, $queryString = [])
    {
        return $this->restClient->get($endpointUrl, $queryString);
    }

    /**
     * @param string $url
     *
     * @return \stdClass
     *
     * @deprecated Will be removed in 3.0
     */
    public function getAttachment($url)
    {
        return $this->restClient->getAttachment($url);
    }

    /**
     * @param string $endpointUrl
     *
     * @return \stdClass
     *
     * @deprecated Will be removed in 3.0
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
     *
     * @deprecated Will be removed in 3.0
     */
    public function put($endpointUrl, $putData)
    {
        return $this->restClient->put($endpointUrl, $putData);
    }

    /**
     * @param string $apiVersion
     *
     * @return Mailgun
     *
     * @deprecated Will be removed in 3.0
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
     *
     * @deprecated Will be removed in 3.0.
     */
    public function MessageBuilder()
    {
        return new MessageBuilder();
    }

    /**
     * @return OptInHandler
     *
     * @deprecated Will be removed in 3.0
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
     *
     * @deprecated Will be removed in 3.0. Use Mailgun::messages()::getBatchMessage().
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
        return new Api\Stats($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Api\Attachment
     */
    public function attachment()
    {
        return new Api\Attachment($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Api\Domain
     */
    public function domains()
    {
        return new Api\Domain($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Api\Tag
     */
    public function tags()
    {
        return new Api\Tag($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Api\Event
     */
    public function events()
    {
        return new Api\Event($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Api\Route
     */
    public function routes()
    {
        return new Api\Route($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Api\Webhook
     */
    public function webhooks()
    {
        return new Api\Webhook($this->httpClient, $this->requestBuilder, $this->hydrator, $this->apiKey);
    }

    /**
     * @return Api\Message
     */
    public function messages()
    {
        return new Api\Message($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return MailingList
     */
    public function mailingList()
    {
        return new MailingList($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Api\Suppression
     */
    public function suppressions()
    {
        return new Api\Suppression($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Api\Ip
     */
    public function ips()
    {
        return new Api\Ip($this->httpClient, $this->requestBuilder, $this->hydrator);
    }
}
