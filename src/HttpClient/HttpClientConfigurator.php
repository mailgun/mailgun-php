<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\HttpClient;

use Http\Client\HttpClient;
use Http\Client\Common\PluginClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\UriFactory;
use Http\Client\Common\Plugin;
use Mailgun\HttpClient\Plugin\History;
use Mailgun\HttpClient\Plugin\ReplaceUriPlugin;

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
     * @var UriFactory
     */
    private $uriFactory;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var History
     */
    private $responseHistory;

    public function __construct()
    {
        $this->responseHistory = new History();
    }

    public function createConfiguredClient(): PluginClient
    {
        $plugins = [
            new Plugin\AddHostPlugin($this->getUriFactory()->createUri($this->endpoint)),
            new Plugin\HeaderDefaultsPlugin([
                'User-Agent' => 'mailgun-sdk-php/v2 (https://github.com/mailgun/mailgun-php)',
                'Authorization' => 'Basic '.base64_encode(sprintf('api:%s', $this->getApiKey())),
            ]),
            new Plugin\HistoryPlugin($this->responseHistory),
        ];

        if ($this->debug) {
            $plugins[] = new ReplaceUriPlugin($this->getUriFactory()->createUri($this->endpoint));
        }

        return new PluginClient($this->getHttpClient(), $plugins);
    }

    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;

        return $this;
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    private function getUriFactory(): UriFactory
    {
        if (null === $this->uriFactory) {
            $this->uriFactory = UriFactoryDiscovery::find();
        }

        return $this->uriFactory;
    }

    public function setUriFactory(UriFactory $uriFactory): self
    {
        $this->uriFactory = $uriFactory;

        return $this;
    }

    private function getHttpClient(): HttpClient
    {
        if (null === $this->httpClient) {
            $this->httpClient = HttpClientDiscovery::find();
        }

        return $this->httpClient;
    }

    public function setHttpClient(HttpClient $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    public function getResponseHistory(): History
    {
        return $this->responseHistory;
    }
}
