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
     * @return ResponseInterface|null
     */
    public function getLastResponse()
    {
        return $this->responseHistory->getLastResponse();
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
     * @return Api\Suppression
     */
    public function suppressions()
    {
        return new Api\Suppression($this->httpClient, $this->requestBuilder, $this->hydrator);
    }
}
