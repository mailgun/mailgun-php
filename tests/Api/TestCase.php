<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mailgun\Hydrator\ModelHydrator;
use Mailgun\Mailgun;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Contributors of https://github.com/KnpLabs/php-github-api
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    private $requestMethod;

    private $requestUri;

    private $requestHeaders;

    private $requestBody;

    private $httpResponse;

    private $hydratedResponse;

    private $hydrateClass;

    protected function setUp()
    {
        $this->reset();
    }

    abstract protected function getApiClass();

    /**
     * This will give you a mocked API. Optionally you can provide mocked dependencies.
     */
    protected function getApiMock($httpClient = null, $requestClient = null, $hydrator = null)
    {
        if (null === $httpClient) {
            $httpClient = $this->getMockBuilder('Http\Client\HttpClient')
                ->setMethods(['sendRequest'])
                ->getMock();
            $httpClient
                ->expects($this->any())
                ->method('sendRequest');
        }
        if (null === $requestClient) {
            $requestClient = $this->getMockBuilder('Mailgun\RequestBuilder')
                ->setMethods(['create'])
                ->getMock();
        }
        if (null === $hydrator) {
            $hydrator = $this->getMockBuilder('Mailgun\Hydrator\Hydrator')
                ->setMethods(['hydrate'])
                ->getMock();
        }

        return $this->getMockBuilder($this->getApiClass())
            ->setMethods(['httpGet', 'httpPost', 'httpPostRaw', 'httpDelete', 'httpPut'])
            ->setConstructorArgs([$httpClient, $requestClient, $hydrator])
            ->getMock();
    }

    /**
     * This will return you a real API instance with mocked dependencies.
     * This will make use of the "setHydratedResponse" and "setRequestMethod" etc..
     */
    protected function getApiInstance($apiKey = null)
    {
        $httpClient = $this->getMockBuilder('Http\Client\HttpClient')
            ->setMethods(['sendRequest'])
            ->getMock();
        $httpClient
            ->method('sendRequest')
            ->willReturn(null === $this->httpResponse ? new Response() : $this->httpResponse);

        $requestClient = $this->getMockBuilder('Mailgun\RequestBuilder')
            ->setMethods(['create'])
            ->getMock();
        $requestClient->method('create')
            ->with(
                $this->callback([$this, 'validateRequestMethod']),
                $this->callback([$this, 'validateRequestUri']),
                $this->callback([$this, 'validateRequestHeaders']),
                $this->callback([$this, 'validateRequestBody'])
            )
            ->willReturn(new Request('GET', '/'));

        $hydrator = new ModelHydrator();
        if (null === $this->httpResponse) {
            $hydrator = $this->getMockBuilder('Mailgun\Hydrator\Hydrator')
                ->setMethods(['hydrate'])
                ->getMock();

            $hydratorModelClass = $this->hydrateClass;
            $hydrateMethod = $hydrator->method('hydrate')
                ->with(
                    $this->callback(function ($response) {
                        return $response instanceof ResponseInterface;
                    }),
                    $this->callback(function ($class) use ($hydratorModelClass) {
                        return null === $hydratorModelClass || $class === $hydratorModelClass;
                    }));

            if (null !== $this->hydratedResponse) {
                $hydrateMethod->willReturn($this->hydratedResponse);
            }
        }

        $class = $this->getApiClass();

        if (null !== $apiKey) {
            return new $class($httpClient, $requestClient, $hydrator, $apiKey);
        }

        return new $class($httpClient, $requestClient, $hydrator);
    }

    public function validateRequestMethod($method)
    {
        return $this->verifyProperty($this->requestMethod, $method);
    }

    public function validateRequestUri($uri)
    {
        return $this->verifyProperty($this->requestUri, $uri);
    }

    public function validateRequestHeaders($headers)
    {
        return $this->verifyProperty($this->requestHeaders, $headers);
    }

    public function validateRequestBody($body)
    {
        if ($this->verifyProperty($this->requestBody, $body)) {
            return true;
        }

        // Assert: $body is prepared for a "multipart stream".

        // Check length
        if (count($this->requestBody) !== count($body)) {
            return false;
        }

        // Check every item in body.
        foreach ($body as $item) {
            if ('resource' === $this->requestBody[$item['name']] && is_resource($item['content'])) {
                continue;
            }
            if ($this->requestBody[$item['name']] !== $item['content']) {
                return false;
            }
        }

        return true;
    }

    protected function reset()
    {
        $this->httpResponse = null;
        $this->hydratedResponse = null;
        $this->requestMethod = null;
        $this->requestUri = null;
        $this->requestHeaders = null;
        $this->requestBody = null;
        $this->hydrateClass = null;
    }

    /**
     * Set a response that you want to client to respond with.
     */
    public function setHttpResponse(ResponseInterface $httpResponse)
    {
        $this->httpResponse = $httpResponse;
    }

    /**
     * The data you want the hydrator to return.
     *
     * @param mixed $hydratedResponse
     */
    public function setHydratedResponse($hydratedResponse)
    {
        $this->hydratedResponse = $hydratedResponse;
    }

    /**
     * Set request http method.
     *
     * @param string $httpMethod
     */
    public function setRequestMethod($httpMethod)
    {
        $this->requestMethod = $httpMethod;
    }

    /**
     * @param string $requestUri
     */
    public function setRequestUri($requestUri)
    {
        $this->requestUri = $requestUri;
    }

    /**
     * @param array $requestHeaders
     */
    public function setRequestHeaders(array $requestHeaders)
    {
        $this->requestHeaders = $requestHeaders;
    }

    /**
     * @param mixed $requestBody
     */
    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
    }

    /**
     * The class we should hydrate to.
     *
     * @param string $hydrateClass
     */
    public function setHydrateClass($hydrateClass)
    {
        $this->hydrateClass = $hydrateClass;
    }

    /**
     * @param mixed|callable $property Example $this->requestMethod
     * @param mixed          $value    the actual value from the user
     *
     * @return bool
     */
    private function verifyProperty($property, $value)
    {
        if (null === $property) {
            return true;
        }

        return is_callable($property) ? call_user_func($property, $value) : $value === $property;
    }

    /**
     * Make sure expectException always exists, even on PHPUnit 4.
     *
     * @param string      $exception
     * @param string|null $message
     */
    public function expectException($exception, $message = null)
    {
        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException($exception, $message);
        } else {
            parent::expectException($exception);
            if (null !== $message) {
                $this->expectExceptionMessage($message);
            }
        }
    }
}
