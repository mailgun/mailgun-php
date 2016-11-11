<?php

namespace Mailgun\Api;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\HttpClient;
use Http\Client\Exception as HttplugException;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\RequestFactory;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Mailgun\Assert;
use Mailgun\Exception\HttpServerException;
use Mailgun\Serializer\ResponseDeserializer;
use Mailgun\Resource\Api\SimpleResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class HttpApi
{
    /**
     * The HTTP client.
     *
     * @var HttpMethodsClient
     */
    private $httpClient;

    /**
     * @var ResponseDeserializer
     */
    protected $serializer;

    /**
     * @param HttpClient           $httpClient
     * @param RequestFactory       $requestFactory
     * @param ResponseDeserializer $serializer
     */
    public function __construct(HttpClient $httpClient, RequestFactory $requestFactory, ResponseDeserializer $serializer)
    {
        $this->httpClient = new HttpMethodsClient($httpClient, $requestFactory);
        $this->serializer = $serializer;
    }

    /**
     * Attempts to safely deserialize the response into the given class.
     * If the HTTP return code != 200, deserializes into SimpleResponse::class
     * to contain the error message and any other information provided.
     *
     * @param ResponseInterface $response
     * @param string            $className
     *
     * @return $class|SimpleResponse
     */
    protected function safeDeserialize(ResponseInterface $response, $className)
    {
        if ($response->getStatusCode() !== 200) {
            return $this->serializer->deserialize($response, SimpleResponse::class);
        } else {
            return $this->serializer->deserialize($response, $className);
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
            $response = $this->httpClient->get($path, $requestHeaders);
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
     * Send a POST request with parameters encoded as multipart-stream form data.
     *
     * @param string $path           Request path.
     * @param array  $parameters     POST parameters to be mutipart-stream-encoded.
     * @param array  $requestHeaders Request headers.
     *
     * @return ResponseInterface
     */
    protected function postMultipart($path, array $parameters = [], array $requestHeaders = [])
    {
        return $this->doMultipart('POST', $path, $parameters, $requestHeaders);
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
    protected function httpPostRaw($path, $body, array $requestHeaders = [])
    {
        try {
            $response = $this->httpClient->post($path, $requestHeaders, $body);
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
            $response = $this->httpClient->put($path, $requestHeaders, $this->createJsonBody($parameters));
        } catch (HttplugException\NetworkException $e) {
            throw HttpServerException::networkError($e);
        }

        return $response;
    }

    /**
     * Send a PUT request with parameters encoded as multipart-stream form data.
     *
     * @param string $path           Request path.
     * @param array  $parameters     PUT parameters to be mutipart-stream-encoded.
     * @param array  $requestHeaders Request headers.
     *
     * @return ResponseInterface
     */
    protected function putMultipart($path, array $parameters = [], array $requestHeaders = [])
    {
        return $this->doMultipart('PUT', $path, $parameters, $requestHeaders);
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
            $response = $this->httpClient->delete($path, $requestHeaders, $this->createJsonBody($parameters));
        } catch (HttplugException\NetworkException $e) {
            throw HttpServerException::networkError($e);
        }

        return $response;
    }

    /**
     * Send a DELETE request with parameters encoded as multipart-stream form data.
     *
     * @param string $path           Request path.
     * @param array  $parameters     DELETE parameters to be mutipart-stream-encoded.
     * @param array  $requestHeaders Request headers.
     *
     * @return ResponseInterface
     */
    protected function deleteMultipart($path, array $parameters = [], array $requestHeaders = [])
    {
        return $this->doMultipart('DELETE', $path, $parameters, $requestHeaders);
    }

    /**
     * Send a request with parameters encoded as multipart-stream form data.
     *
     * @param string $type           Request type. (POST, PUT, etc.)
     * @param string $path           Request path.
     * @param array  $parameters     POST parameters to be mutipart-stream-encoded.
     * @param array  $requestHeaders Request headers.
     *
     * @return ResponseInterface
     */
    protected function doMultipart($type, $path, array $parameters = [], array $requestHeaders = [])
    {
        Assert::oneOf(
            $type,
            [
                'DELETE',
                'POST',
                'PUT',
            ]
        );

        $streamFactory = StreamFactoryDiscovery::find();
        $builder = new MultipartStreamBuilder($streamFactory);
        foreach ($parameters as $k => $v) {
            $builder->addResource($k, $v);
        }

        $multipartStream = $builder->build();
        $boundary = $builder->getBoundary();

        $request = MessageFactoryDiscovery::find()->createRequest(
            $type,
            $path,
            ['Content-Type' => 'multipart/form-data; boundary='.$boundary],
            $multipartStream
        );

        return $this->httpClient->sendRequest($request);
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
