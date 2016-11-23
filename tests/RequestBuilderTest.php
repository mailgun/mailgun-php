<?php

namespace Mailgun\Tests;

use Mailgun\RequestBuilder;

class RequestBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateSimpleStream()
    {
        $builder = new RequestBuilder();
        $request = $builder->create('GET', 'http://foo.bar', ['Content-Type'=>'application/json'], 'content');

        $body = $request->getBody()->__toString();
        $contentType = $request->getHeaderLine('Content-Type');

        $this->assertContains('content', $body);
        $this->assertEquals('application/json', $contentType);
    }


    public function testCreateMultipartStream()
    {
        $item0 = ['content'=>'foobar', 'name'=>'username'];
        $item1 = ['content'=>'Stockholm', 'name'=>'city'];

        $builder = new RequestBuilder();
        $request = $builder->create('GET', 'http://foo.bar', ['Content-Type'=>'application/json'], [$item0, $item1]);

        $body = $request->getBody()->__toString();
        $contentType = $request->getHeaderLine('Content-Type');

        $this->assertContains('foobar', $body);
        $this->assertContains('username', $body);
        $this->assertContains('Stockholm', $body);
        $this->assertContains('city', $body);
        $this->assertRegExp('|^multipart/form-data; boundary=[0-9a-z]+$|si', $contentType);
    }
}
