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
use Mailgun\Api\Ip;
use Mailgun\Model\Ip\IndexResponse;

class IpTest extends TestCase
{
    protected function getApiClass()
    {
        return Ip::class;
    }

    public function testIndexAll()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ips');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "assignable_to_pools": ["192.168.0.1"],
  "items": ["192.161.0.1", "192.168.0.2"],
  "total_count": 2
}
JSON
        ));

        $api = $this->getApiInstance();
        /** @var IndexResponse $response */
        $response = $api->index(null);
        $this->assertInstanceOf(IndexResponse::class, $response);
        $this->assertEquals(2, $response->getTotalCount());
        $this->assertEquals('192.161.0.1', $response->getItems()[0]);
        $this->assertEquals('192.168.0.2', $response->getItems()[1]);
        $this->assertEquals('192.168.0.1', $response->getAssignableToPools()[0]);
    }
    
    public function testIndexOnlyDedicated()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ips?dedicated=1');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "assignable_to_pools": ["192.168.0.1"],
  "items": ["192.161.0.1"],
  "total_count": 1
}
JSON
        ));

        $api = $this->getApiInstance();
        /** @var IndexResponse $response */
        $response = $api->index(true);
        $this->assertInstanceOf(IndexResponse::class, $response);
        $this->assertEquals(1, $response->getTotalCount());
        $this->assertEquals('192.161.0.1', $response->getItems()[0]);
        $this->assertEquals('192.168.0.1', $response->getAssignableToPools()[0]);
    }
    
    public function testIndexOnlyShared()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ips?dedicated=0');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "assignable_to_pools": ["192.168.0.1"],
  "items": ["192.168.0.2"],
  "total_count": 1
}
JSON
        ));

        $api = $this->getApiInstance();
        /** @var IndexResponse $response */
        $response = $api->index(false);
        $this->assertInstanceOf(IndexResponse::class, $response);
        $this->assertEquals(1, $response->getTotalCount());
        $this->assertEquals('192.168.0.2', $response->getItems()[0]);
        $this->assertEquals('192.168.0.1', $response->getAssignableToPools()[0]);
    }
}
