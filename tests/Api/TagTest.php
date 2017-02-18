<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use GuzzleHttp\Psr7\Response;
use Mailgun\Api\Tag;

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

        $model = $api->index('domain', 10);
        $this->assertInstanceOf($model, IndexResponse::class);
    }

    public function testShow()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/domain/tags/foo')
            ->willReturn(new Response());

        $model = $api->show('domain', 'foo');
        $this->assertInstanceOf($model, ShowResponse::class);
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

        $model = $api->update('domain', 'foo', 'desc');
        $this->assertInstanceOf($model, UpdateResponse::class);
    }

    public function testStats()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/domain/tags/foo/stats')
            ->willReturn(new Response());

        $model = $api->stats('domain', 'foo');
        $this->assertInstanceOf($model, StatisticsResponse::class);
    }

    public function testDelete()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpDelete')
            ->with('/v3/domain/tags/foo')
            ->willReturn(new Response());

        $model = $api->delete('domain', 'foo');
        $this->assertInstanceOf($model, DeleteResponse::class);
    }
}
