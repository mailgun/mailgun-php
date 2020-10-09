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
use Mailgun\Api\Domain;
use Mailgun\Model\Domain\ConnectionResponse;
use Mailgun\Model\Domain\CreateCredentialResponse;
use Mailgun\Model\Domain\CreateResponse;
use Mailgun\Model\Domain\DeleteCredentialResponse;
use Mailgun\Model\Domain\DeleteResponse;
use Mailgun\Model\Domain\IndexResponse;
use Mailgun\Model\Domain\ShowResponse;
use Mailgun\Model\Domain\TrackingResponse;
use Mailgun\Model\Domain\UpdateClickTrackingResponse;
use Mailgun\Model\Domain\UpdateConnectionResponse;
use Mailgun\Model\Domain\UpdateCredentialResponse;
use Mailgun\Model\Domain\UpdateOpenTrackingResponse;
use Mailgun\Model\Domain\UpdateUnsubscribeTrackingResponse;
use Mailgun\Model\Domain\VerifyResponse;

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
            'name' => 'example.com',
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
            'name' => 'example.com',
            'smtp_password' => 'foo',
        ]);
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', 'foo');
    }

    public function testCreateWithPasswordSpamAction()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/domains');
        $this->setRequestBody([
            'name' => 'example.com',
            'smtp_password' => 'foo',
            'spam_action' => 'bar',
        ]);
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', 'foo', 'bar');
    }

    public function testCreateWithPasswordSpamActionWildcard()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/domains');
        $this->setRequestBody([
            'name' => 'example.com',
            'smtp_password' => 'foo',
            'spam_action' => 'bar',
            'wildcard' => 'true',
        ]);
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', 'foo', 'bar', true);
    }

    public function testCreateWithPasswordForceDkimAuthority()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/domains');
        $this->setRequestBody([
            'name' => 'example.com',
            'smtp_password' => 'foo',
            'force_dkim_authority' => 'true',
        ]);
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', 'foo', null, null, true);
    }

    public function testCreateWithPasswordSpamActionWildcardForceDkimAuthority()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/domains');
        $this->setRequestBody([
            'name' => 'example.com',
            'smtp_password' => 'foo',
            'spam_action' => 'bar',
            'wildcard' => 'true',
            'force_dkim_authority' => 'true',
        ]);
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', 'foo', 'bar', true, true);
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
        $api->updateConnection('example.com', true, false);
    }

    public function testVerify()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/verify');
        $this->setHydrateClass(VerifyResponse::class);

        $api = $this->getApiInstance();
        $api->verify('example.com');
    }

    public function testTracking()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/domains/example.com/tracking');
        $this->setHydrateClass(TrackingResponse::class);

        /**
         * @var $api Domain
         */
        $api = $this->getApiInstance();
        $api->tracking('example.com');
    }

    public function activeInactiveDataProvider(): array
    {
        return [
            [true],
            [false],
        ];
    }

    /**
     * @dataProvider activeInactiveDataProvider
     */
    public function testUpdateClickTracking(bool $isActive)
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/tracking/click');
        $this->setRequestBody([
            'active' => $isActive ? 'true' : 'false',
        ]);
        $this->setHydrateClass(UpdateClickTrackingResponse::class);

        /**
         * @var $api Domain
         */
        $api = $this->getApiInstance();
        $api->updateClickTracking('example.com', $isActive);
    }

    /**
     * @dataProvider activeInactiveDataProvider
     */
    public function testUpdateOpenTracking(bool $isActive)
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/tracking/open');
        $this->setRequestBody([
            'active' => $isActive ? 'true' : 'false',
        ]);
        $this->setHydrateClass(UpdateOpenTrackingResponse::class);

        /**
         * @var $api Domain
         */
        $api = $this->getApiInstance();
        $api->updateOpenTracking('example.com', $isActive);
    }

    public function unsubscribeDataProvider(): array
    {
        return [
            [true, '<b>Test</b>', 'Test1'],
            [false, '<s>Test</s>', 'Test2'],
        ];
    }

    /**
     * @dataProvider unsubscribeDataProvider
     * @param bool $isActive
     * @param string $htmlFooter
     * @param string $textFooter
     */
    public function testUpdateUnsubscribeTracking(bool $isActive, string $htmlFooter, string $textFooter)
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/tracking/unsubscribe');
        $this->setRequestBody([
            'active' => $isActive ? 'true' : 'false',
            'html_footer' => $htmlFooter,
            'text_footer' => $textFooter,
        ]);
        $this->setHydrateClass(UpdateUnsubscribeTrackingResponse::class);

        /**
         * @var $api Domain
         */
        $api = $this->getApiInstance();
        $api->updateUnsubscribeTracking('example.com', $isActive, $htmlFooter, $textFooter);
    }
}
