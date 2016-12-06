<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun;

use Http\Client\HttpClient;
use Http\Client\Common\PluginClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\UriFactory;
use Http\Client\Common\Plugin;

/**
 * Configure a HTTP client.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class HttpClientConfigurator
{
    /**
     * @var string
     */
    private $endpoint = 'https://api.mailgun.net';

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
     * @return PluginClient
     */
    public function createConfiguredClient()
    {
        $plugins = [
            new Plugin\AddHostPlugin($this->getUriFactory()->createUri($this->getEndpoint())),
            new Plugin\HeaderDefaultsPlugin([
                'User-Agent' => 'mailgun-sdk-php/v2 (https://github.com/mailgun/mailgun-php)',
                'Authorization' => 'Basic '.base64_encode(sprintf('api:%s', $this->getApiKey())),
            ]),
        ];

        return new PluginClient($this->getHttpClient(), $plugins);
    }

    /**
     * @return string
     */
    private function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     *
     * @return HttpClientConfigurator
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @return string
     */
    private function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     *
     * @return HttpClientConfigurator
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return UriFactory
     */
    private function getUriFactory()
    {
        if ($this->uriFactory === null) {
            $this->uriFactory = UriFactoryDiscovery::find();
        }

        return $this->uriFactory;
    }

    /**
     * @param UriFactory $uriFactory
     *
     * @return HttpClientConfigurator
     */
    public function setUriFactory(UriFactory $uriFactory)
    {
        $this->uriFactory = $uriFactory;

        return $this;
    }

    /**
     * @return HttpClient
     */
    private function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = HttpClientDiscovery::find();
        }

        return $this->httpClient;
    }

    /**
     * @param HttpClient $httpClient
     *
     * @return HttpClientConfigurator
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }
}
