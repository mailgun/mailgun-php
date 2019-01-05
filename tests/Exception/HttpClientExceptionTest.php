<?php

namespace Mailgun\Tests\Exception;

use GuzzleHttp\Psr7\Response;
use Mailgun\Exception\HttpClientException;
use Mailgun\Tests\MailgunTestCase;

class HttpClientExceptionTest extends MailgunTestCase
{
    public function testBadRequestGetMessageJson()
    {
        $response = new Response(400, ['Content-Type'=>'application/json'], '{"message":"Server Message"}');
        $exception = HttpClientException::badRequest($response);
        $this->assertStringEndsWith('Server Message', $exception->getMessage());
    }

    public function testBadRequestGetMessage()
    {
        $response = new Response(400, ['Content-Type'=>'text/html'], '<html><body>Server HTML</body></html>');
        $exception = HttpClientException::badRequest($response);
        $this->assertStringEndsWith('<html><body>Server HTML</body></html>', $exception->getMessage());
    }
}