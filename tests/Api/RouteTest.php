<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use GuzzleHttp\Psr7\Response;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
class RouteTest extends TestCase
{
    public function testCreate()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPost')
            ->willReturn(new Response());

        $api->create('catch_all()', ['forward("mailbox@myapp.com")'], 'example', 100);
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiClass()
    {
        return 'Mailgun\Api\Route';
    }
}
