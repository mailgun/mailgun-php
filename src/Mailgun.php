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
use Mailgun\HttpClient\Plugin\History;
use Mailgun\Hydrator\ModelHydrator;
use Mailgun\Hydrator\Hydrator;
use Psr\Http\Message\ResponseInterface;

/**
 * This class is the base class for the Mailgun SDK.
 */
final class Mailgun
{
    /**
     * @var null|string
     */
    private $apiKey;

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
    private $responseHistory;

    public function __construct(
        HttpClientConfigurator $configurator,
        Hydrator $hydrator = null,
        RequestBuilder $requestBuilder = null
    ) {
        $this->requestBuilder = $requestBuilder ?: new RequestBuilder();
        $this->hydrator = $hydrator ?: new ModelHydrator();

        $this->httpClient = $configurator->createConfiguredClient();
        $this->apiKey = $configurator->getApiKey();
        $this->responseHistory = $configurator->getResponseHistory();
    }

    public static function create(string $apiKey, string $endpoint = 'https://api.mailgun.net'): self
    {
        $httpClientConfigurator = (new HttpClientConfigurator())
            ->setApiKey($apiKey)
            ->setEndpoint($endpoint);

        return new self($httpClientConfigurator);
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
