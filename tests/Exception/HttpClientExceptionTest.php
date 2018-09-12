<?php

namespace Mailgun\Tests\Exception;

use Mailgun\Exception\HttpClientException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpClientExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testBadRequestGetMessage()
    {
        $response = new ResponseMock();
        $exception = HttpClientException::badRequest($response);
        $this->assertEquals('Server Message', $exception->getMessage());
    }

    public function testBadRequestGetMessageNotJson()
    {
        $response = new ResponseMock('text/html', '<html><body>Server HTML</body></html>');
        $exception = HttpClientException::badRequest($response);
        $this->assertEquals('The parameters passed to the API were invalid. Check your inputs!', $exception->getMessage());
    }
}

class StreamMock implements StreamInterface
{
    private $string;
    public function __construct($string)
    {
        $this->string = $string;
    }
    public function __toString() { return $this->string; }
    public function close() {}
    public function detach() { return null; }
    public function getSize() { return 0; }
    public function tell() { return 0; }
    public function eof() { return true; }
    public function isSeekable() { return false; }
    public function seek($offset, $whence = SEEK_SET) {}
    public function rewind() {}
    public function isWritable() { return false; }
    public function write($string) { return 0; }
    public function isReadable() { return true; }
    public function read($length) { return ''; }
    public function getContents() { return ''; }
    public function getMetadata($key = null) { return null; }
}

class ResponseMock implements ResponseInterface
{
    private $headerLine;
    private $body;
    public function __construct($headerLine = 'application/json', $body = '{"message":"Server Message"}')
    {
        $this->headerLine = $headerLine;
        $this->body = $body;
    }
    public function getStatusCode() { return 400; }
    public function withStatus($code, $reasonPhrase = '') { return $this; }
    public function getReasonPhrase() { return $this->reasonPhrase; }

    public function getProtocolVersion() { return '1.0'; }
    public function withProtocolVersion($version) { return $this; }
    public function getHeaders() { return []; }
    public function hasHeader($name) { return false; }
    public function getHeader($name) { return null; }
    public function getHeaderLine($name) { return $this->headerLine; }
    public function withHeader($name, $value) { return $this; }
    public function withAddedHeader($name, $value) { return $this; }
    public function withoutHeader($name) { return $this; }
    public function getBody() { return new StreamMock($this->body); }
    public function withBody(StreamInterface $body) { return $this; }
}
