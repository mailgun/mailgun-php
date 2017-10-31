<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use Http\Message\RequestFactory;
use Mailgun\RequestBuilder;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

class RequestBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MockObject|RequestFactory
     */
    private $requestFactory;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * Environment preset.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestFactory = $this->getMockBuilder(RequestFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestBuilder = new RequestBuilder();
        //Everything but testing class is mock. Otherwise it wouldn't be unit testing
        $this->requestBuilder->setRequestFactory($this->requestFactory);
    }

    /**
     * Environment reset.
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->requestFactory = null;
        $this->requestBuilder = null;
    }

    public function testCreateSimpleStream()
    {
        $this->requestFactory
            ->expects($this->once())
            ->method('createRequest')
            ->with(
                $this->equalTo('GET'),
                $this->equalTo('http://foo.bar'),
                $this->callback(function (array $headers) {
                    $this->assertArrayHasKey('Content-Type', $headers);
                    $this->assertEquals('application/json', $headers['Content-Type']);

                    return true;
                }),
                $this->equalTo('content')
            )
            ->willReturn($request = $this->getMock(RequestInterface::class));

        $result = $this->requestBuilder
            ->create('GET', 'http://foo.bar', ['Content-Type' => 'application/json'], 'content');

        $this->assertSame($request, $result);
    }

    public function testCreateMultipartStream()
    {
        $multipartStreamBuilder = $this->createMultipartStreamBuilder();

        $item0 = ['content' => 'foobar', 'name' => 'username', 'some_stuff' => 'some value'];
        $item1 = ['content' => 'Stockholm', 'name' => 'city', 'other_stuff' => 'other value'];
        $body = [$item0, $item1];

        foreach ($body as $index => $item) {
            $multipartStreamBuilder
                ->expects($this->at($index))
                ->method('addResource')
                ->with(
                    $this->equalTo($item['name']),
                    $this->equalTo($item['content']),
                    $this->callback(function (array $data) use ($item) {
                        unset($item['name'], $item['content']);
                        $this->assertEquals($item, $data);

                        return true;
                    })
                )
                ->willReturn($multipartStreamBuilder);
        }

        $multipartStreamBuilder
            ->expects($this->once())
            ->method('build')
            ->willReturn($stream = $this->getMock(StreamInterface::class));

        $multipartStreamBuilder
            ->expects($this->once())
            ->method('getBoundary')
            ->willReturn('some boundary');

        $multipartStreamBuilder
            ->expects($this->once())
            ->method('reset')
            ->willReturn($multipartStreamBuilder);

        $this->requestFactory
            ->expects($this->once())
            ->method('createRequest')
            ->with(
                $this->equalTo('GET'),
                $this->equalTo('http://foo.bar'),
                $this->callback(function (array $headers) {
                    $this->assertArrayHasKey('Content-Type', $headers);
                    $this->assertEquals('multipart/form-data; boundary="some boundary"', $headers['Content-Type']);

                    return true;
                }),
                $this->equalTo($stream)
            )
            ->willReturn($request = $this->getMock(RequestInterface::class));

        $this->requestBuilder->setMultipartStreamBuilder($multipartStreamBuilder);
        $result = $this->requestBuilder
            ->create('GET', 'http://foo.bar', ['Content-Type' => 'application/json'], [$item0, $item1]);

        $this->assertSame($request, $result);
    }

    /**
     * Creates multipart stream builder.
     *
     * @return MockObject|MultipartStreamBuilder
     */
    private function createMultipartStreamBuilder()
    {
        return $this->getMockBuilder(MultipartStreamBuilder::class)->disableOriginalConstructor()->getMock();
    }
}
