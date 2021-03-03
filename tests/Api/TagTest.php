<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use GuzzleHttp\Psr7\Response;
use Mailgun\Api\Tag;
use Mailgun\Hydrator\ModelHydrator;
use Mailgun\Model\Tag\IndexResponse;

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
        $jsonResponse = '{
    "items": [
        {
            "tag": "Tag1",
            "description": "",
            "first-seen": "2020-11-02T00:00:00Z",
            "last-seen": "2020-11-02T19:14:07.747Z"
        },
        {
            "tag": "Tag10",
            "description": "",
            "first-seen": "2020-11-02T00:00:00Z",
            "last-seen": "2020-11-02T19:12:06.408Z"
        },
        {
            "tag": "Tag11",
            "description": "",
            "first-seen": "2020-11-02T00:00:00Z",
            "last-seen": "2020-11-02T19:12:06.105Z"
        },
        {
            "tag": "Tag2",
            "description": "",
            "first-seen": "2020-11-02T00:00:00Z",
            "last-seen": "2020-11-02T19:14:09.111Z"
        },
        {
            "tag": "Tag3",
            "description": "",
            "first-seen": "2020-11-02T00:00:00Z",
            "last-seen": "2020-11-02T19:14:08.772Z"
        },
        {
            "tag": "Tag9",
            "description": "",
            "first-seen": "2020-11-02T00:00:00Z",
            "last-seen": "2020-11-02T19:12:06.214Z"
        }
    ],
    "paging": {
        "previous": "http:\/\/api.mailgun.net\/v3\/sandbox152fb160643f41f9b09b52f7b5e370ec.mailgun.org\/tags?limit=10&page=prev&tag=",
        "first": "http:\/\/api.mailgun.net\/v3\/sandbox152fb160643f41f9b09b52f7b5e370ec.mailgun.org\/tags?limit=10&page=first&tag=",
        "next": "http:\/\/api.mailgun.net\/v3\/sandbox152fb160643f41f9b09b52f7b5e370ec.mailgun.org\/tags?limit=10&page=next&tag=",
        "last": "http:\/\/api.mailgun.net\/v3\/sandbox152fb160643f41f9b09b52f7b5e370ec.mailgun.org\/tags?limit=10&page=last&tag="
    }
}';

        $data = [
            'limit' => 10,
        ];

        $api = $this->getApiMock(null, null, new ModelHydrator());
        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/domain/tags', $data)
            ->willReturn(new Response(200, ['Content-Type' => 'application/json'], $jsonResponse));

        $tags = $api->index('domain', 10);

        $this->assertInstanceOf(IndexResponse::class, $tags);
        $this->assertCount(6, $tags->getItems());
        $this->assertContainsOnlyInstancesOf(\Mailgun\Model\Tag\Tag::class, $tags->getItems());
        $this->assertTrue(method_exists($api, 'nextPage'));
        $this->assertTrue(method_exists($api, 'previousPage'));
        $this->assertTrue(method_exists($api, 'firstPage'));
        $this->assertTrue(method_exists($api, 'lastPage'));
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
