<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Exception;

use Nyholm\Psr7\Response;
use Mailgun\Exception\HttpClientException;
use Mailgun\Tests\MailgunTestCase;

class HttpClientExceptionTest extends MailgunTestCase
{
    public function testBadRequestGetMessageJson()
    {
        $response = new Response(400, ['Content-Type' => 'application/json'], '{"message":"Server Message"}');
        $exception = HttpClientException::badRequest($response);
        $this->assertStringEndsWith('Server Message', $exception->getMessage());

        $response = new Response(400, ['Content-Type' => 'application/json'], '{"Foo":"Server Message"}');
        $exception = HttpClientException::badRequest($response);
        $this->assertStringEndsWith('{"Foo":"Server Message"}', $exception->getMessage());
    }

    public function testBadRequestGetMessage()
    {
        $response = new Response(400, ['Content-Type' => 'text/html'], '<html><body>Server HTML</body></html>');
        $exception = HttpClientException::badRequest($response);
        $this->assertStringEndsWith('<html><body>Server HTML</body></html>', $exception->getMessage());
    }

    public function testForbiddenRequestThrowException()
    {
        $response = new Response(403, ['Content-Type' => 'application/json'], '{"Error":"Business Verification"}');
        $exception = HttpClientException::forbidden($response);
        $this->assertInstanceOf(HttpClientException::class, $exception);
        $this->assertSame(403, $exception->getCode());
    }

    public function testForbiddenRequestGetMessageJson()
    {
        $response = new Response(403, ['Content-Type' => 'application/json'], '{"Error":"Business Verification"}');
        $exception = HttpClientException::forbidden($response);
        $this->assertStringEndsWith('Business Verification', $exception->getMessage());

        $response = new Response(403, ['Content-Type' => 'application/json'], '{"Message":"Business Verification"}');
        $exception = HttpClientException::forbidden($response);
        $this->assertStringEndsWith('{"Message":"Business Verification"}', $exception->getMessage());
    }

    public function testForbiddenRequestGetMessage()
    {
        $response = new Response(403, ['Content-Type' => 'text/html'], '<html><body>Forbidden</body></html>');
        $exception = HttpClientException::forbidden($response);
        $this->assertStringEndsWith('<html><body>Forbidden</body></html>', $exception->getMessage());
    }

    public function testTooManyRequestsGetMessage()
    {
        $response = new Response(429, ['Content-Type' => 'text/html']);
        $exception = HttpClientException::tooManyRequests($response);
        $this->assertEquals('Too many requests.', $exception->getMessage());
        $this->assertEquals($response->getStatusCode(), $exception->getCode());
    }
}
