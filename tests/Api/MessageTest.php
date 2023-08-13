<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\Message;
use Mailgun\Model\Message\SendResponse;
use Mailgun\Model\Message\ShowResponse;
use Nyholm\Psr7\Response;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class MessageTest extends TestCase
{
    public function testSend()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/example.com/messages');
        $this->setHydrateClass(SendResponse::class);
        $this->setRequestBody(
            [
            'from' => 'bob@example.com',
            'to' => 'sally@example.com',
            'subject' => 'Test file path attachments',
            'text' => 'Test',
            'attachment' => 'resource',
            ]
        );

        $api = $this->getApiInstance();
        $api->send(
            'example.com', [
            'from' => 'bob@example.com',
            'to' => 'sally@example.com',
            'subject' => 'Test file path attachments',
            'text' => 'Test',
            'attachment' => [
                ['filePath' => __DIR__.'/../TestAssets/mailgun_icon1.png', 'filename' => 'mailgun_icon1.png'],
            ],
            ]
        );
    }

    public function testSendMime()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPostRaw')
            ->with(
                '/v3/foo/messages.mime',
                $this->callback(
                    function ($multipartBody) {
                        $parameters = ['o:Foo' => 'bar', 'to' => 'mailbox@myapp.com'];

                        // Verify all parameters
                        foreach ($parameters as $name => $content) {
                            $found = false;
                            foreach ($multipartBody as $body) {
                                if ($body['name'] === $name && $body['content'] === $content) {
                                    $found = true;
                                }
                            }
                            if (!$found) {
                                return false;
                            }
                        }

                        $found = false;
                        foreach ($multipartBody as $body) {
                            if ('message' === $body['name']) {
                                // Make sure message exists.
                                $found = true;
                                // Make sure content is what we expect
                                if (!isset($body['content'])) {
                                    return false;
                                }
                            }
                        }
                        if (!$found) {
                            return false;
                        }

                        return true;
                    }
                )
            )
            ->willReturn(new Response());

        $api->sendMime('foo', ['mailbox@myapp.com'], 'mime message', ['o:Foo' => 'bar']);
    }

    public function testShow()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('url');
        $this->setHydrateClass(ShowResponse::class);

        $api = $this->getApiInstance();
        $api->show('url');
    }

    public function testShowRaw()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('url');
        $this->setRequestHeaders(
            [
            'Accept' => 'message/rfc2822',
            ]
        );
        $this->setHydrateClass(ShowResponse::class);

        $api = $this->getApiInstance();
        $api->show('url', true);
    }

    public function testSendMimeWithLongMessage()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPostRaw')
            ->willReturn(new Response());

        $message = str_repeat('a', PHP_MAXPATHLEN).' and some more';
        $api->sendMime('foo', ['mailbox@myapp.com'], $message, []);
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiClass()
    {
        return Message::class;
    }
}
