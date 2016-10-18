<?php

namespace Mailgun\Api;

use Mailgun\Exception\HttpServerException;
use Mailgun\HttpClient\ResponseMediator;
use Mailgun\Mailgun;
use Http\Client\Exception as HttplugException;
use Mailgun\Serializer\ResponseSerializer;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Contributors of https://github.com/KnpLabs/php-github-api
 */
abstract class AbstractApi
{
    /**
     * The client.
     *
     * @var Mailgun
     */
    protected $mailgun;

    /**
     * @var ResponseSerializer
     */
    protected $serializer;

    /**
     *
     * @param Mailgun $client
     */
    public function __construct(Mailgun $mailgun, ResponseSerializer $serializer)
    {
        $this->mailgun = $mailgun;
        $this->serializer = $serializer;
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
    protected function get($path, array $parameters = [], $requestHeaders = [])
    {
        if (count($parameters) > 0) {
            $path .= '?'.http_build_query($parameters);
        }

        try {
            $response = $this->mailgun->getHttpClient()->get($path, $requestHeaders);
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
    protected function post($path, array $parameters = [], $requestHeaders = [])
    {
        return $this->postRaw(
            $path,
            $this->createJsonBody($parameters),
            $requestHeaders
        );
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string $path           Request path.
     * @param string $body           Request body.
     * @param array  $requestHeaders Request headers.
     *
     * @return ResponseInterface
     */
    protected function postRaw($path, $body, $requestHeaders = [])
    {
        try {
            $response = $this->mailgun->getHttpClient()->post(
                $path,
                $requestHeaders,
                $body
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
    protected function put($path, array $parameters = [], $requestHeaders = [])
    {
        try {
            $response = $this->mailgun->getHttpClient()->put(
                $path,
                $requestHeaders,
                $this->createJsonBody($parameters)
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
    protected function delete($path, array $parameters = [], $requestHeaders = [])
    {
        try {
            $response = $this->mailgun->getHttpClient()->delete(
                $path,
                $requestHeaders,
                $this->createJsonBody($parameters)
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
