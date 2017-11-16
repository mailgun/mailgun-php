<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\tests\Api;

use GuzzleHttp\Psr7\Response;
use Mailgun\Api\Tag;
use Mailgun\Mailgun;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class TagTest extends TestCase
{
    protected function getApiClass()
    {
        return Tag::class;
    }

    public function testIndex()
    {
        $data = [
            'limit' => 10,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/domain/tags', $data)
            ->willReturn(new Response());

        $api->index('domain', 10);
    }

    public function testShow()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/domain/tags/foo')
            ->willReturn(new Response());

        $api->show('domain', 'foo');
    }

    public function testUpdate()
    {
        $data = [
            'description' => 'desc',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPut')
            ->with('/v3/domain/tags/foo', $data)
            ->willReturn(new Response());

        $api->update('domain', 'foo', 'desc');
    }

    public function testStats()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/domain/tags/foo/stats', ['event' => 'foo'])
            ->willReturn(new Response());

        $api->stats('domain', 'foo', ['event' => 'foo']);
    }

    public function testDelete()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpDelete')
            ->with('/v3/domain/tags/foo')
            ->willReturn(new Response());

        $api->delete('domain', 'foo');
    }
}
