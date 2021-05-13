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
use Mailgun\Exception\HttpClientException;
use Mailgun\Exception\HttpServerException;
use Mailgun\Exception\UnknownErrorException;
use Mailgun\HttpClient\RequestBuilder;
use Mailgun\Hydrator\Hydrator;
use Mailgun\Hydrator\NoopHydrator;
use Psr\Http\Client as Psr18;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class HttpApi
{
    /**
     * The HTTP client.
     *
     * @var ClientInterface|PluginClient
     */
    protected $httpClient;

    /**
     * @var Hydrator|null
     */
    protected $hydrator;

    /**
     * @var RequestBuilder
     */
    protected $requestBuilder;

    public function __construct($httpClient, RequestBuilder $requestBuilder, Hydrator $hydrator)
    {
        if (!is_a($httpClient, ClientInterface::class) &&
            !is_a($httpClient, PluginClient::class)) {
            throw new \RuntimeException('httpClient must be an instance of 
            Psr\Http\Client\ClientInterface or Http\Client\Common\PluginClient');
        }
        $this->httpClient = $httpClient;
        $this->requestBuilder = $requestBuilder;
        if (!$hydrator instanceof NoopHydrator) {
            $this->hydrator = $hydrator;
        }
    }

    /**
     * @return mixed|ResponseInterface
     *
     * @throws \Exception
     */
    protected function hydrateResponse(ResponseInterface $response, string $class)
    {
        if (null === $this->hydrator) {
            return $response;
        }

        if (!in_array($response->getStatusCode(), [200, 201, 202], true)) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, $class);
    }

    /**
     * Throw the correct exception for this error.
     *
     * @throws \Exception
     */
    protected function handleErrors(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        switch ($statusCode) {
            case 400:
                throw HttpClientException::badRequest($response);
            case 401:
                throw HttpClientException::unauthorized($response);
            case 402:
                throw HttpClientException::requestFailed($response);
            case 403:
                throw HttpClientException::forbidden($response);
            case 404:
                throw HttpClientException::notFound($response);
            case 409:
                throw HttpClientException::conflict($response);
            case 413:
                throw HttpClientException::payloadTooLarge($response);
            case 429:
                throw HttpClientException::tooManyRequests($response);
            case 500 <= $statusCode:
                throw HttpServerException::serverError($statusCode);
            default:
                throw new UnknownErrorException();
        }
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path           Request path
     * @param array  $parameters     GET parameters
     * @param array  $requestHeaders Request Headers
     */
    protected function httpGet(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        if (count($parameters) > 0) {
            $path .= '?'.http_build_query($parameters);
        }

        try {
            $response = $this->httpClient->sendRequest(
                $this->requestBuilder->create('GET', $path, $requestHeaders)
            );
        } catch (Psr18\NetworkExceptionInterface $e) {
            throw HttpServerException::networkError($e);
        }

        return $response;
    }

    /**
     * Send a POST request with parameters.
     *
     * @param string $path           Request path
     * @param array  $parameters     POST parameters
     * @param array  $requestHeaders Request headers
     */
    protected function httpPost(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->httpPostRaw($path, $this->createRequestBody($parameters), $requestHeaders);
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string       $path           Request path
     * @param array|string $body           Request body
     * @param array        $requestHeaders Request headers
     */
    protected function httpPostRaw(string $path, $body, array $requestHeaders = []): ResponseInterface
    {
        try {
            $response = $this->httpClient->sendRequest(
                $this->requestBuilder->create('POST', $path, $requestHeaders, $body)
            );
        } catch (Psr18\NetworkExceptionInterface $e) {
            throw HttpServerException::networkError($e);
        }

        return $response;
    }

    /**
     * Send a PUT request.
     *
     * @param string $path           Request path
     * @param array  $parameters     PUT parameters
     * @param array  $requestHeaders Request headers
     */
    protected function httpPut(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        try {
            $response = $this->httpClient->sendRequest(
                $this->requestBuilder->create('PUT', $path, $requestHeaders, $this->createRequestBody($parameters))
            );
        } catch (Psr18\NetworkExceptionInterface $e) {
            throw HttpServerException::networkError($e);
        }

        return $response;
    }

    /**
     * Send a DELETE request.
     *
     * @param string $path           Request path
     * @param array  $parameters     DELETE parameters
     * @param array  $requestHeaders Request headers
     */
    protected function httpDelete(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface
    {
        try {
            $response = $this->httpClient->sendRequest(
                $this->requestBuilder->create('DELETE', $path, $requestHeaders, $this->createRequestBody($parameters))
            );
        } catch (Psr18\NetworkExceptionInterface $e) {
            throw HttpServerException::networkError($e);
        }

        return $response;
    }

    /**
     * Prepare a set of key-value-pairs to be encoded as multipart/form-data.
     *
     * @param array $parameters Request parameters
     */
    private function createRequestBody(array $parameters): array
    {
        $resources = [];
        foreach ($parameters as $key => $values) {
            if (!is_array($values)) {
                $values = [$values];
            }
            foreach ($values as $value) {
                $resources[] = [
                    'name' => $key,
                    'content' => $value,
                ];
            }
        }

        return $resources;
    }
}
