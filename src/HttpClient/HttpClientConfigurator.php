<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\HttpClient;

use Composer\InstalledVersions;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Mailgun\HttpClient\Plugin\History;
use Mailgun\HttpClient\Plugin\ReplaceUriPlugin;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * Configure a HTTP client.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class HttpClientConfigurator
{
    /**
     * @var string
     */
    private $endpoint = 'https://api.mailgun.net';

    /**
     * If debug is true we will send all the request to the endpoint without appending any path.
     *
     * @var bool
     */
    private $debug = false;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var History
     */
    private $responseHistory;

    /**
     * @var ?string
     */
    private $subAccountId;

    public function __construct()
    {
        $this->responseHistory = new History();
    }

    /**
     * @return PluginClient
     */
    public function createConfiguredClient(): PluginClient
    {
        $userAgent = InstalledVersions::getVersion('mailgun/mailgun-php');
        if (!isset($userAgent) || !$userAgent) {
            $userAgent = 'mailgun-sdk-php/v2 (https://github.com/mailgun/mailgun-php)';
        }

        $defaultPlugin = [
            'User-Agent' => $userAgent,
            'Authorization' => 'Basic '.base64_encode(sprintf('api:%s', $this->getApiKey())),
        ];
        if (null !== $this->getSubAccountId()) {
            $defaultPlugin['X-Mailgun-On-Behalf-Of'] = $this->getSubAccountId();
        }

        $plugins = [
            new Plugin\AddHostPlugin($this->getUriFactory()->createUri($this->endpoint)),
            new Plugin\HeaderDefaultsPlugin($defaultPlugin),
            new Plugin\HistoryPlugin($this->responseHistory),
        ];

        if ($this->debug) {
            $plugins[] = new ReplaceUriPlugin($this->getUriFactory()->createUri($this->endpoint));
        }

        return new PluginClient($this->getHttpClient(), $plugins);
    }

    /**
     * @param bool $debug
     * @return $this
     */
    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return UriFactoryInterface
     */
    private function getUriFactory(): UriFactoryInterface
    {
        if (null === $this->uriFactory) {
            $this->uriFactory = Psr17FactoryDiscovery::findUrlFactory();
        }

        return $this->uriFactory;
    }

    /**
     * @param UriFactoryInterface $uriFactory
     * @return $this
     */
    public function setUriFactory(UriFactoryInterface $uriFactory): self
    {
        $this->uriFactory = $uriFactory;

        return $this;
    }

    /**
     * @return ClientInterface
     */
    private function getHttpClient(): ClientInterface
    {
        if (null === $this->httpClient) {
            $this->httpClient = Psr18ClientDiscovery::find();
        }

        return $this->httpClient;
    }

    /**
     * @param ClientInterface $httpClient
     * @return $this
     */
    public function setHttpClient(ClientInterface $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @return History
     */
    public function getResponseHistory(): History
    {
        return $this->responseHistory;
    }

    /**
     * @return string|null
     */
    public function getSubAccountId(): ?string
    {
        return $this->subAccountId;
    }

    /**
     * @param  string|null            $subAccountId
     * @return HttpClientConfigurator
     */
    public function setSubAccountId(?string $subAccountId): self
    {
        $this->subAccountId = $subAccountId;

        return $this;
    }
}
