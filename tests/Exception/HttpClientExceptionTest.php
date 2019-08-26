<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Exception;

use GuzzleHttp\Psr7\Response;
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
}
