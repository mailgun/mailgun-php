<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Http\Client\Common\PluginClient;
use Mailgun\HttpClient\RequestBuilder;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @see    https://documentation.mailgun.com/api-domains.html
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class HttpClient extends HttpApi
{
    /**
     * @return PluginClient|ClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @return RequestBuilder
     */
    public function getRequestBuilder(): RequestBuilder
    {
        return $this->requestBuilder;
    }

    /**
     * @param  string                   $path
     * @param  array                    $parameters
     * @param  array                    $requestHeaders
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function httpDelete(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        return parent::httpDelete($path, $parameters, $requestHeaders);
    }

    /**
     * @param  string                   $path
     * @param  array                    $parameters
     * @param  array                    $requestHeaders
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function httpGet(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        return parent::httpGet($path, $parameters, $requestHeaders);
    }

    /**
     * @param  string                   $path
     * @param  array                    $parameters
     * @param  array                    $requestHeaders
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function httpPost(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        return parent::httpPost($path, $parameters, $requestHeaders);
    }

    /**
     * @param  string                   $path
     * @param  array                    $parameters
     * @param  array                    $requestHeaders
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function httpPut(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        return parent::httpPut($path, $parameters, $requestHeaders);
    }

    /**
     * @param  string                   $path
     * @param  array|string             $body
     * @param  array                    $requestHeaders
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function httpPostRaw(string $path, $body, array $requestHeaders = []): ResponseInterface
    {
        return parent::httpPostRaw($path, $body, $requestHeaders);
    }
}
