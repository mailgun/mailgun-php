<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun;

use Http\Client\Common\PluginClient;
use Mailgun\Api\Attachment;
use Mailgun\Api\Domain;
use Mailgun\Api\EmailValidation;
use Mailgun\Api\EmailValidationV4;
use Mailgun\Api\Event;
use Mailgun\Api\HttpClient;
use Mailgun\Api\Ip;
use Mailgun\Api\Mailboxes;
use Mailgun\Api\MailingList;
use Mailgun\Api\Message;
use Mailgun\Api\Metrics;
use Mailgun\Api\Route;
use Mailgun\Api\Stats;
use Mailgun\Api\SubAccounts;
use Mailgun\Api\Suppression;
use Mailgun\Api\Tag;
use Mailgun\Api\Templates;
use Mailgun\Api\Webhook;
use Mailgun\HttpClient\HttpClientConfigurator;
use Mailgun\HttpClient\Plugin\History;
use Mailgun\HttpClient\RequestBuilder;
use Mailgun\Hydrator\Hydrator;
use Mailgun\Hydrator\ModelHydrator;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * This class is the base class for the Mailgun SDK.
 */
class Mailgun
{
    /**
     * @var string|null
     */
    private ?string $apiKey;

    /**
     * @var ClientInterface|PluginClient
     */
    private $httpClient;

    /**
     * @var Hydrator
     */
    private Hydrator $hydrator;

    /**
     * @var RequestBuilder
     */
    private RequestBuilder $requestBuilder;

    /**
     * This is a object that holds the last response from the API.
     *
     * @var History
     */
    private History $responseHistory;

    /**
     * @param HttpClientConfigurator $configurator
     * @param Hydrator|null          $hydrator
     * @param RequestBuilder|null    $requestBuilder
     */
    public function __construct(
        HttpClientConfigurator $configurator,
        ?Hydrator $hydrator = null,
        ?RequestBuilder $requestBuilder = null
    ) {
        $this->requestBuilder = $requestBuilder ?: new RequestBuilder();
        $this->hydrator = $hydrator ?: new ModelHydrator();

        $this->httpClient = $configurator->createConfiguredClient();
        $this->apiKey = $configurator->getApiKey();
        $this->responseHistory = $configurator->getResponseHistory();
    }

    /**
     * @param  string      $apiKey
     * @param  string      $endpoint
     * @param  string|null $subAccountId
     * @return self
     */
    public static function create(string $apiKey, string $endpoint = 'https://api.mailgun.net', ?string $subAccountId = null): self
    {
        $httpClientConfigurator = (new HttpClientConfigurator())
            ->setApiKey($apiKey)
            ->setEndpoint($endpoint)
            ->setSubAccountId($subAccountId);

        return new self($httpClientConfigurator);
    }

    /**
     * @return ResponseInterface|null
     */
    public function getLastResponse(): ?ResponseInterface
    {
        return $this->responseHistory->getLastResponse();
    }

    /**
     * @return Attachment
     */
    public function attachment(): Api\Attachment
    {
        return new Api\Attachment($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Domain
     */
    public function domains(): Api\Domain
    {
        return new Api\Domain($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Api\DomainV4
     */
    public function domainsV4(): Api\DomainV4
    {
        return new Api\DomainV4($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return EmailValidation
     */
    public function emailValidation(): Api\EmailValidation
    {
        return new Api\EmailValidation($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return EmailValidationV4
     */
    public function emailValidationV4(): Api\EmailValidationV4
    {
        return new Api\EmailValidationV4($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Event
     */
    public function events(): Api\Event
    {
        return new Api\Event($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Ip
     */
    public function ips(): Api\Ip
    {
        return new Api\Ip($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return MailingList
     */
    public function mailingList(): Api\MailingList
    {
        return new Api\MailingList($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Message
     */
    public function messages(): Api\Message
    {
        return new Api\Message($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Route
     */
    public function routes(): Api\Route
    {
        return new Api\Route($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Suppression
     */
    public function suppressions(): Api\Suppression
    {
        return new Api\Suppression($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Stats
     */
    public function stats(): Api\Stats
    {
        return new Api\Stats($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Tag
     */
    public function tags(): Api\Tag
    {
        return new Api\Tag($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Webhook
     */
    public function webhooks(): Api\Webhook
    {
        return new Api\Webhook($this->httpClient, $this->requestBuilder, $this->hydrator, $this->apiKey ?? '');
    }

    /**
     * @return Mailboxes
     */
    public function mailboxes(): Api\Mailboxes
    {
        return new Api\Mailboxes($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return HttpClient
     */
    public function httpClient(): Api\HttpClient
    {
        return new Api\HttpClient($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return SubAccounts
     */
    public function subaccounts(): Api\SubAccounts
    {
        return new Api\SubAccounts($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Templates
     */
    public function templates(): Templates
    {
        return new Templates($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Metrics
     */
    public function metrics(): Metrics
    {
        return new Metrics($this->httpClient, $this->requestBuilder, $this->hydrator);
    }
}
