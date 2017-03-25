<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Http\Client\Exception as HttplugException;
use Http\Client\HttpClient;
use Mailgun\Exception\UnknownErrorException;
use Mailgun\Hydrator\Hydrator;
use Mailgun\Hydrator\NoopHydrator;
use Mailgun\Exception\HttpClientException;
use Mailgun\Exception\HttpServerException;
use Mailgun\RequestBuilder;
use Mailgun\Model\ErrorResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class HttpApi
{
    /**
     * The HTTP client.
     *
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Hydrator
     */
    protected $hydrator;

    /**
     * @var RequestBuilder
     */
    protected $requestBuilder;

    /**
     * @param HttpClient     $httpClient
     * @param RequestBuilder $requestBuilder
     * @param Hydrator       $hydrator
     */
    public function __construct(HttpClient $httpClient, RequestBuilder $requestBuilder, Hydrator $hydrator)
    {
        $this->httpClient = $httpClient;
        $this->requestBuilder = $requestBuilder;
        if (!$hydrator instanceof NoopHydrator) {
            $this->hydrator = $hydrator;
        }
    }


    /**
     * @param ResponseInterface $response
     * @param string $class
     *
     * @return mixed|array|ResponseInterface
     *
     * @throws \Exception
     */
    protected function hydrateResponse(ResponseInterface $response, $class)
    {
        if (!$this->hydrator) {
            return $response;
        }

        if ($response->getStatusCode() !== 200) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, $class);
    }

    /**
     * Throw the correct exception for this error.
     *
     * @param ResponseInterface $response
     *
     * @throws \Exception
     */
    protected function handleErrors(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        if (400 === $statusCode) {
            throw HttpClientException::badRequest($response);
        } elseif (401 === $statusCode) {
            throw HttpClientException::unauthorized($response);
        } elseif (402 === $statusCode) {
            throw HttpClientException::requestFailed($response);
        } elseif (404 === $statusCode) {
            throw HttpClientException::notFound($response);
        } elseif (500 <= $statusCode) {
            throw HttpServerException::serverError($statusCode);
        } else {
            throw new UnknownErrorException();
        }
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path           Request path.
     * @param array  $parameters     GET parameters.
     * @param array  $requestHeaders Request Headers.
     *
     * @return ResponseInterface
     */
    protected function httpGet($path, array $parameters = [], array $requestHeaders = [])
    {
        if (count($parameters) > 0) {
            $path .= '?'.http_build_query($parameters);
        }

        try {
            $response = $this->httpClient->sendRequest(
                $this->requestBuilder->create('GET', $path, $requestHeaders)
            );
        } catch (HttplugException\NetworkException $e) {
            throw HttpServerException::networkError($e);
        }

        return $response;
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param string $path           Request path.
     * @param array  $parameters     POST parameters to be JSON encoded.
     * @param array  $requestHeaders Request headers.
     *
     * @return ResponseInterface
     */
    protected function httpPost($path, array $parameters = [], array $requestHeaders = [])
    {
        return $this->httpPostRaw($path, $this->createJsonBody($parameters), $requestHeaders);
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string       $path           Request path.
     * @param array|string $body           Request body.
     * @param array        $requestHeaders Request headers.
     *
     * @return ResponseInterface
     */
    protected function httpPostRaw($path, $body, array $requestHeaders = [])
    {
        try {
            $response = $this->httpClient->sendRequest(
                $this->requestBuilder->create('POST', $path, $requestHeaders, $body)
            );
        } catch (HttplugException\NetworkException $e) {
            throw HttpServerException::networkError($e);
        }

        return $response;
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param string $path           Request path.
     * @param array  $parameters     POST parameters to be JSON encoded.
     * @param array  $requestHeaders Request headers.
     *
     * @return ResponseInterface
     */
    protected function httpPut($path, array $parameters = [], array $requestHeaders = [])
    {
        try {
            $response = $this->httpClient->sendRequest(
                $this->requestBuilder->create('PUT', $path, $requestHeaders, $this->createJsonBody($parameters))
            );
        } catch (HttplugException\NetworkException $e) {
            throw HttpServerException::networkError($e);
        }

        return $response;
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param string $path           Request path.
     * @param array  $parameters     POST parameters to be JSON encoded.
     * @param array  $requestHeaders Request headers.
     *
     * @return ResponseInterface
     */
    protected function httpDelete($path, array $parameters = [], array $requestHeaders = [])
    {
        try {
            $response = $this->httpClient->sendRequest(
                $this->requestBuilder->create('DELETE', $path, $requestHeaders, $this->createJsonBody($parameters))
            );
        } catch (HttplugException\NetworkException $e) {
            throw HttpServerException::networkError($e);
        }

        return $response;
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param array $parameters Request parameters
     *
     * @return null|string
     */
    protected function createJsonBody(array $parameters)
    {
        return (count($parameters) === 0) ? null : json_encode($parameters, empty($parameters) ? JSON_FORCE_OBJECT : 0);
    }
}
