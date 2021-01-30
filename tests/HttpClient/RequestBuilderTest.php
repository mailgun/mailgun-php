<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\HttpClient;

use Http\Message\MultipartStream\MultipartStreamBuilder;
use Mailgun\HttpClient\RequestBuilder;
use Mailgun\Tests\MailgunTestCase;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class RequestBuilderTest extends MailgunTestCase
{
    /**
     * @var MockObject|RequestFactoryInterface
     */
    private $requestFactory;
    /**
     * @var MockObject|StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * Environment preset.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->requestFactory = $this->getMockBuilder(RequestFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->streamFactory = $this->getMockBuilder(StreamFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestBuilder = new RequestBuilder();
        //Everything but testing class is mock. Otherwise it wouldn't be unit testing
        $this->requestBuilder->setRequestFactory($this->requestFactory);
        $this->requestBuilder->setStreamFactory($this->streamFactory);
    }

    /**
     * Environment reset.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->requestFactory = null;
        $this->requestBuilder = null;
    }

    public function testCreateSimpleStream()
    {
        $streamContent = 'content';
        $stream = Stream::create($streamContent);

        $this->streamFactory
            ->expects($this->once())
            ->method('createStream')
            ->with($streamContent)
            ->willReturn($stream);

        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $request->expects($this->once())
            ->method('withBody')
            ->with($this->equalTo($stream))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('withAddedHeader')
            ->with($this->equalTo('Content-Type'), $this->equalTo('application/json'))
            ->willReturn($request);

        $this->requestFactory
            ->expects($this->once())
            ->method('createRequest')
            ->with(
                $this->equalTo('GET'),
                $this->equalTo('http://foo.bar')
            )
            ->willReturn($request);

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
            ->willReturn($stream = $this->getMockBuilder(StreamInterface::class)->getMock());

        $multipartStreamBuilder
            ->expects($this->once())
            ->method('getBoundary')
            ->willReturn('some boundary');

        $multipartStreamBuilder
            ->expects($this->once())
            ->method('reset')
            ->willReturn($multipartStreamBuilder);

        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $request->expects($this->once())
            ->method('withBody')
            ->with($this->equalTo($stream))
            ->willReturn($request);

        $request->expects($this->once())
            ->method('withAddedHeader')
            ->with($this->equalTo('Content-Type'), $this->equalTo('multipart/form-data; boundary="some boundary"'))
            ->willReturn($request);

        $this->requestFactory
            ->expects($this->once())
            ->method('createRequest')
            ->with(
                $this->equalTo('GET'),
                $this->equalTo('http://foo.bar')
            )
            ->willReturn($request);

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
