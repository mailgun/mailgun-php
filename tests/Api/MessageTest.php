<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use GuzzleHttp\Psr7\Response;
use Mailgun\Api\Message;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class MessageTest extends TestCase
{
    public function testSendMime()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPostRaw')
            ->with('/v3/foo/messages.mime',
                $this->callback(function ($multipartBody) {
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
                            if (!is_resource($body['content'])) {
                                return false;
                            }
                        }
                    }
                    if (!$found) {
                        return false;
                    }

                    return true;
                }))
            ->willReturn(new Response());

        $api->sendMime('foo', ['mailbox@myapp.com'], 'mime message', ['o:Foo' => 'bar']);
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiClass()
    {
        return Message::class;
    }
}
