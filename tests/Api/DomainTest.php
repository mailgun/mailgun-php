<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use GuzzleHttp\Psr7\Response;
use Mailgun\Api\Domain;
use Mailgun\Api\Event;
use Mailgun\Exception\InvalidArgumentException;
use Mailgun\Model\Domain\ConnectionResponse;
use Mailgun\Model\Domain\CreateCredentialResponse;
use Mailgun\Model\Domain\CreateResponse;
use Mailgun\Model\Domain\DeleteCredentialResponse;
use Mailgun\Model\Domain\DeleteResponse;
use Mailgun\Model\Domain\IndexResponse;
use Mailgun\Model\Domain\ShowResponse;
use Mailgun\Model\Domain\UpdateConnectionResponse;
use Mailgun\Model\Domain\UpdateCredentialResponse;
use Mailgun\Model\Domain\VerifyResponse;
use Mailgun\Model\Event\EventResponse;

class DomainTest extends TestCase
{
    protected function getApiClass()
    {
        return Domain::class;
    }

    public function testIndex()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/domains?limit=100&skip=0');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "total_count": 1,
  "items": [
    {
      "created_at": "Wed, 10 Jul 2013 19:26:52 GMT",
      "smtp_login": "postmaster@samples.mailgun.org",
      "name": "samples.mailgun.org",
      "smtp_password": "4rtqo4p6rrx9",
      "wildcard": true,
      "spam_action": "disabled",
      "state": "active"
    }
  ]
}
JSON
));

        $api = $this->getApiInstance();
        /** @var IndexResponse $response */
        $response = $api->index();
        $this->assertInstanceOf(IndexResponse::class, $response);
        $this->assertEquals(1, $response->getTotalCount());
        $this->assertEquals('samples.mailgun.org', $response->getDomains()[0]->getName());
    }

    public function testShow()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/domains/example.com');
        $this->setHydrateClass(ShowResponse::class);

        $api = $this->getApiInstance();
        $api->show('example.com');
    }

    public function testCreate()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/domains');
        $this->setRequestBody([
            'name'=>'example.com'
        ]);
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com');
    }

    public function testCreateWithPassword()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/domains');
        $this->setRequestBody([
            'name'=>'example.com',
            'smtp_password'=>'foo',
            'spam_action'=>'bar',
        ]);
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', 'foo', 'bar', true);
    }

    public function testDelete()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/domains/example.com');
        $this->setHydrateClass(DeleteResponse::class);

        $api = $this->getApiInstance();
        $api->delete('example.com');
    }

    public function testCreateCredential()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/domains/example.com/credentials');
        $this->setRequestBody([
            'login' => 'foo',
            'password' => 'barbar',
        ]);
        $this->setHydrateClass(CreateCredentialResponse::class);

        $api = $this->getApiInstance();
        $api->createCredential('example.com', 'foo', 'barbar');
    }

    public function testUpdateCredential()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/credentials/foo');
        $this->setRequestBody([
            'password' => 'barbar',
        ]);
        $this->setHydrateClass(UpdateCredentialResponse::class);

        $api = $this->getApiInstance();
        $api->updateCredential('example.com', 'foo', 'barbar');
    }
    public function testDeleteCredential()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/domains/example.com/credentials/foo');
        $this->setHydrateClass(DeleteCredentialResponse::class);

        $api = $this->getApiInstance();
        $api->deleteCredential('example.com', 'foo', 'barbar');
    }

    public function testConnection()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/domains/example.com/connection');
        $this->setHydrateClass(ConnectionResponse::class);

        $api = $this->getApiInstance();
        $api->connection('example.com');
    }

    public function testUpdateConnection()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/connection');
        $this->setRequestBody([
            'require_tls' => 'true',
            'skip_verification' => 'false',
        ]);
        $this->setHydrateClass(UpdateConnectionResponse::class);

        $api = $this->getApiInstance();
        $api->updateConnection('example.com', true , false);
    }


    public function testVerify()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/verify');
        $this->setHydrateClass(VerifyResponse::class);

        $api = $this->getApiInstance();
        $api->verify('example.com');
    }


}
