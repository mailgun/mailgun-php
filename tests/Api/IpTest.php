<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\Ip;
use Mailgun\Model\Ip\AvailableIpsResponse;
use Mailgun\Model\Ip\IndexResponse;
use Mailgun\Model\Ip\IpDetailsResponse;
use Mailgun\Model\Ip\IpPoolDomainsResponse;
use Mailgun\Model\Ip\IpPoolResponse;
use Mailgun\Model\Ip\IpPoolsResponse;
use Mailgun\Model\Ip\IpReferenceResponse;
use Mailgun\Model\Ip\ShowResponse;
use Mailgun\Model\Ip\UpdateResponse;
use Nyholm\Psr7\Response;

class IpTest extends TestCase
{
    protected function getApiClass()
    {
        return Ip::class;
    }

    // -------------------------------------------------------------------------
    // IPs
    // -------------------------------------------------------------------------

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
JSON));

        $response = $this->getApiInstance()->index(null);

        $this->assertInstanceOf(IndexResponse::class, $response);
        $this->assertEquals(2, $response->getTotalCount());
        $this->assertEquals(['192.161.0.1', '192.168.0.2'], $response->getItems());
        $this->assertEquals(['192.168.0.1'], $response->getAssignableToPools());
    }

    public function testIndexOnlyDedicated()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ips?dedicated=1');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "assignable_to_pools": [],
  "items": ["192.161.0.1"],
  "total_count": 1
}
JSON));

        $response = $this->getApiInstance()->index(true);

        $this->assertInstanceOf(IndexResponse::class, $response);
        $this->assertEquals(1, $response->getTotalCount());
    }

    public function testIndexOnlyShared()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ips?dedicated=0');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "assignable_to_pools": [],
  "items": ["192.168.0.2"],
  "total_count": 1
}
JSON));

        $response = $this->getApiInstance()->index(false);

        $this->assertInstanceOf(IndexResponse::class, $response);
        $this->assertEquals(1, $response->getTotalCount());
    }

    public function testShow()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ips/192.168.0.1');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "ip": "192.168.0.1",
  "dedicated": true,
  "rdns": "mail.example.com"
}
JSON));

        $response = $this->getApiInstance()->show('192.168.0.1');

        $this->assertInstanceOf(ShowResponse::class, $response);
        $this->assertEquals('192.168.0.1', $response->getIp());
        $this->assertTrue($response->getDedicated());
        $this->assertEquals('mail.example.com', $response->getRdns());
    }

    public function testDomainsByIp()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ips/192.168.0.1/domains?limit=10&skip=0');
        $this->setHydrateClass(IndexResponse::class);

        $this->getApiInstance()->domainsByIp('192.168.0.1');
    }

    public function testDomainsByIpWithSearch()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ips/192.168.0.1/domains?limit=5&skip=10&search=example');
        $this->setHydrateClass(IndexResponse::class);

        $this->getApiInstance()->domainsByIp('192.168.0.1', 5, 10, 'example');
    }

    public function testAssignIpToAllDomains()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/ips/192.168.0.1/domains');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "message": "IP assignment queued",
  "reference_id": "ref-abc-123"
}
JSON));

        $response = $this->getApiInstance()->assignIpToAllDomains('192.168.0.1');

        $this->assertInstanceOf(IpReferenceResponse::class, $response);
        $this->assertEquals('IP assignment queued', $response->getMessage());
        $this->assertEquals('ref-abc-123', $response->getReferenceId());
    }

    public function testRemoveIpFromAllDomains()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/ips/192.168.0.1/domains?alternative=10.0.0.1');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "message": "IP removal queued",
  "reference_id": "ref-def-456"
}
JSON));

        $response = $this->getApiInstance()->removeIpFromAllDomains('192.168.0.1', '10.0.0.1');

        $this->assertInstanceOf(IpReferenceResponse::class, $response);
        $this->assertEquals('ref-def-456', $response->getReferenceId());
    }

    public function testPlaceAccountIpToBand()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/ips/192.168.0.1/ip_band');
        $this->setRequestBody(['ip_band' => '192.168.0.1']);
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->placeAccountIpToBand('192.168.0.1');
    }

    public function testNumberOfIps()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ips/request/new');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "available_ips": 5,
  "total_ips": 10
}
JSON));

        $response = $this->getApiInstance()->numberOfIps();

        $this->assertInstanceOf(AvailableIpsResponse::class, $response);
    }

    public function testAddDedicatedIp()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/ips/request/new');
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->addDedicatedIp();
    }

    public function testListIpsDetailed()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ips/details/all');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "items": [
    {
      "address": "192.168.0.1",
      "dedicated": true,
      "account_id": "acc-123",
      "pool_ids": ["pool-abc"]
    }
  ],
  "total_count": 1
}
JSON));

        $response = $this->getApiInstance()->listIpsDetailed();

        $this->assertInstanceOf(IpDetailsResponse::class, $response);
        $this->assertEquals(1, $response->getTotalCount());
        $this->assertCount(1, $response->getItems());
        $this->assertEquals('192.168.0.1', $response->getItems()[0]['address']);
    }

    public function testListIpsDetailedWithFilters()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ips/details/all?pool_id=pool-abc&limit=5');
        $this->setHydrateClass(IpDetailsResponse::class);

        $this->getApiInstance()->listIpsDetailed(['pool_id' => 'pool-abc', 'limit' => 5]);
    }

    // -------------------------------------------------------------------------
    // Domain IPs
    // -------------------------------------------------------------------------

    public function testDomainIndex()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/domains/example.com/ips');
        $this->setHydrateClass(IndexResponse::class);

        $this->getApiInstance()->domainIndex('example.com');
    }

    public function testAssign()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/domains/example.com/ips');
        $this->setRequestBody(['ip' => '127.0.0.1']);
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->assign('example.com', '127.0.0.1');
    }

    public function testUnassign()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/domains/example.com/ips/192.168.0.1');
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->unassign('example.com', '192.168.0.1');
    }

    public function testRemoveIpOrUnlink()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/domains/example.com/pool/192.168.0.1');
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->removeIpOrUnlink('example.com', '192.168.0.1');
    }

    // -------------------------------------------------------------------------
    // IP Pools (DIPPs)
    // -------------------------------------------------------------------------

    public function testListIpPools()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ip_pools');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "ip_pools": [
    {
      "pool_id": "pool-abc",
      "name": "Primary Pool",
      "description": "Main dedicated IP pool",
      "ips": ["192.0.2.1", "192.0.2.2"],
      "is_inherited": false,
      "is_linked": true
    }
  ],
  "message": "success"
}
JSON));

        $response = $this->getApiInstance()->listIpPools();

        $this->assertInstanceOf(IpPoolsResponse::class, $response);
        $this->assertEquals('success', $response->getMessage());
        $this->assertCount(1, $response->getIpPools());
        $this->assertEquals('pool-abc', $response->getIpPools()[0]['pool_id']);
    }

    public function testCreateIpPool()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/ip_pools');
        $this->setRequestBody(['name' => 'My Pool', 'description' => 'Test pool']);
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->createIpPool('My Pool', 'Test pool');
    }

    public function testLoadDIPPInformation()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ip_pools/pool-abc');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "pool_id": "pool-abc",
  "name": "Primary Pool",
  "description": "Main dedicated IP pool",
  "ips": ["192.0.2.1", "192.0.2.2"],
  "is_inherited": false,
  "is_linked": true
}
JSON));

        $response = $this->getApiInstance()->loadDIPPInformation('pool-abc');

        $this->assertInstanceOf(IpPoolResponse::class, $response);
        $this->assertEquals('pool-abc', $response->getPoolId());
        $this->assertEquals('Primary Pool', $response->getName());
        $this->assertEquals('Main dedicated IP pool', $response->getDescription());
        $this->assertEquals(['192.0.2.1', '192.0.2.2'], $response->getIps());
        $this->assertFalse($response->isInherited());
        $this->assertTrue($response->isLinked());
    }

    public function testUpdateIpPool()
    {
        $this->setRequestMethod('PATCH');
        $this->setRequestUri('/v3/ip_pools/pool-abc');
        $this->setRequestBody(['add_ip' => '192.0.2.3', 'name' => 'Updated Pool']);
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->updateIpPool('pool-abc', ['add_ip' => '192.0.2.3', 'name' => 'Updated Pool']);
    }

    public function testDeleteDIPPWithoutReplacement()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/ip_pools/pool-abc');
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->deleteDIPP('pool-abc');
    }

    public function testDeleteDIPPWithReplacementIp()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/ip_pools/pool-abc?ip=192.0.2.1');
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->deleteDIPP('pool-abc', '192.0.2.1');
    }

    public function testDeleteDIPPWithReplacementPool()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/ip_pools/pool-abc?pool_id=pool-xyz');
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->deleteDIPP('pool-abc', null, 'pool-xyz');
    }

    public function testGetIpPoolDomains()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ip_pools/pool-abc/domains?limit=10');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "domains": [
    {"name": "example.com"},
    {"name": "another.com"}
  ],
  "paging": {
    "next": "encoded-page-token"
  }
}
JSON));

        $response = $this->getApiInstance()->getIpPoolDomains('pool-abc');

        $this->assertInstanceOf(IpPoolDomainsResponse::class, $response);
        $this->assertCount(2, $response->getDomains());
        $this->assertEquals('encoded-page-token', $response->getNextPage());
    }

    public function testGetIpPoolDomainsWithPage()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/ip_pools/pool-abc/domains?limit=5&page=encoded-page-token');
        $this->setHydrateClass(IpPoolDomainsResponse::class);

        $this->getApiInstance()->getIpPoolDomains('pool-abc', 5, 'encoded-page-token');
    }

    public function testAddIpToPool()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/ip_pools/pool-abc/ips/192.0.2.1');
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->addIpToPool('pool-abc', '192.0.2.1');
    }

    public function testRemoveIpFromPool()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/ip_pools/pool-abc/ips/192.0.2.1');
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->removeIpFromPool('pool-abc', '192.0.2.1');
    }

    public function testAddIpsToPool()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/ip_pools/pool-abc/ips.json');
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->addIpsToPool('pool-abc', ['192.0.2.1', '192.0.2.2']);
    }

    public function testDelegateIpPool()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/ip_pools/pool-abc/delegate');
        $this->setRequestBody(['subaccount_id' => 'sub-123']);
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->delegateIpPool('pool-abc', 'sub-123');
    }

    public function testRevokeDelegatedIpPool()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/ip_pools/pool-abc/delegate');
        $this->setRequestBody(['subaccount_id' => 'sub-123']);
        $this->setHydrateClass(UpdateResponse::class);

        $this->getApiInstance()->revokeDelegatedIpPool('pool-abc', 'sub-123');
    }
}
