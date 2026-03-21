<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\DomainV4;
use Mailgun\Exception\InvalidArgumentException;
use Mailgun\Model\Domain\ConnectionResponse;
use Mailgun\Model\Domain\CreateCredentialResponse;
use Mailgun\Model\Domain\CreateResponse;
use Mailgun\Model\Domain\CredentialResponse;
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
use Mailgun\Model\Domain\UseAutomaticSenderSecurity;
use Mailgun\Model\Domain\VerifyResponse;
use Mailgun\Model\Domain\WebPrefixResponse;
use Mailgun\Model\Domain\WebSchemeResponse;
use Nyholm\Psr7\Response;

class DomainV4Test extends TestCase
{
    protected function getApiClass()
    {
        return DomainV4::class;
    }

    public function testIndex()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v4/domains?limit=100&skip=0');
        $this->setHttpResponse(
            new Response(
                200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "total_count": 2,
  "items": [
    {
      "created_at": "Wed, 10 Jul 2013 19:26:52 GMT",
      "name": "samples.mailgun.org",
      "state": "active",
      "web_scheme": "http"
    },
    {
      "created_at": "Thu, 11 Jul 2013 10:15:32 GMT",
      "name": "test.mailgun.org",
      "state": "unverified",
      "web_scheme": "https"
    }
  ]
}
JSON
            )
        );

        $api = $this->getApiInstance();
        /**
         * @var IndexResponse $response
         */
        $response = $api->index();
        $this->assertInstanceOf(IndexResponse::class, $response);
        $this->assertEquals(2, $response->getTotalCount());
        $this->assertEquals('samples.mailgun.org', $response->getDomains()[0]->getName());
    }

    public function testIndexWithCustomParams()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v4/domains?limit=50&skip=10&state=active&sort=name%3Aasc');
        $this->setHydrateClass(IndexResponse::class);

        $api = $this->getApiInstance();
        $api->index(50, 10, ['state' => 'active', 'sort' => 'name:asc']);
    }

    public function testIndexLimitOutOfRangeException()
    {
        $this->expectException(InvalidArgumentException::class);

        $api = $this->getApiInstance();
        $api->index(1001);
    }

    public function testIndexInvalidStateException()
    {
        $this->expectException(InvalidArgumentException::class);

        $api = $this->getApiInstance();
        $api->index(100, 0, ['state' => 'invalid']);
    }

    public function testIndexInvalidSortException()
    {
        $this->expectException(InvalidArgumentException::class);

        $api = $this->getApiInstance();
        $api->index(100, 0, ['sort' => 'invalid']);
    }

    public function testShow()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v4/domains/example.com');
        $this->setHydrateClass(ShowResponse::class);

        $api = $this->getApiInstance();
        $api->show('example.com');
    }

    public function testCreate()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/domains');
        $this->setRequestBody(
            [
                'name' => 'example.com',
                'web_scheme' => 'http',
                'dkim_key_size' => '1024',
            ]
        );
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com');
    }

    public function testCreateWithPassword()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/domains');
        $this->setRequestBody(
            [
                'name' => 'example.com',
                'smtp_password' => 'secret123',
                'web_scheme' => 'http',
                'dkim_key_size' => '1024',
            ]
        );
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', 'secret123');
    }

    public function testCreateWithPasswordAndSpamAction()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/domains');
        $this->setRequestBody(
            [
                'name' => 'example.com',
                'smtp_password' => 'secret123',
                'spam_action' => 'tag',
                'web_scheme' => 'http',
                'dkim_key_size' => '1024',
            ]
        );
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', 'secret123', 'tag');
    }

    public function testCreateWithWildcard()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/domains');
        $this->setRequestBody(
            [
                'name' => 'example.com',
                'wildcard' => 'true',
                'web_scheme' => 'http',
                'dkim_key_size' => '1024',
            ]
        );
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', null, null, true);
    }

    public function testCreateWithForceDkimAuthority()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/domains');
        $this->setRequestBody(
            [
                'name' => 'example.com',
                'force_dkim_authority' => 'true',
                'web_scheme' => 'http',
                'dkim_key_size' => '1024',
            ]
        );
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', null, null, null, true);
    }

    public function testCreateWithIps()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/domains');
        $this->setRequestBody(
            [
                'name' => 'example.com',
                'ips' => '192.168.1.1,192.168.1.2',
                'web_scheme' => 'http',
                'dkim_key_size' => '1024',
            ]
        );
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', null, null, null, null, ['192.168.1.1', '192.168.1.2']);
    }

    public function testCreateWithPoolId()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/domains');
        $this->setRequestBody(
            [
                'name' => 'example.com',
                'pool_id' => 'pool123',
                'web_scheme' => 'http',
                'dkim_key_size' => '1024',
            ]
        );
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', null, null, null, null, null, 'pool123');
    }

    public function testCreateWithHttpsWebScheme()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/domains');
        $this->setRequestBody(
            [
                'name' => 'example.com',
                'web_scheme' => 'https',
                'dkim_key_size' => '1024',
            ]
        );
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', null, null, null, null, null, null, 'https');
    }

    public function testCreateWith2048DkimKeySize()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/domains');
        $this->setRequestBody(
            [
                'name' => 'example.com',
                'web_scheme' => 'http',
                'dkim_key_size' => '2048',
            ]
        );
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', null, null, null, null, null, null, 'http', '2048');
    }

    public function testCreateWithDkimHostName()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/domains');
        $this->setRequestBody(
            [
                'name' => 'example.com',
                'web_scheme' => 'http',
                'dkim_key_size' => '1024',
                'dkim_host_name' => 'dkim.example.com',
            ]
        );
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', null, null, null, null, null, null, 'http', '1024', [], 'dkim.example.com');
    }

    public function testCreateWithDkimSelector()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/domains');
        $this->setRequestBody(
            [
                'name' => 'example.com',
                'web_scheme' => 'http',
                'dkim_key_size' => '1024',
                'dkim_selector' => 'selector1',
            ]
        );
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', null, null, null, null, null, null, 'http', '1024', [], null, 'selector1');
    }

    public function testCreateWithUseAutomaticSenderSecurity()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v4/domains');
        $this->setRequestBody(
            [
                'name' => 'example.com',
                'web_scheme' => 'http',
                'dkim_key_size' => '1024',
                'use_automatic_sender_security' => 'true',
            ]
        );
        $this->setHydrateClass(CreateResponse::class);

        $api = $this->getApiInstance();
        $api->create('example.com', null, null, null, null, null, null, 'http', '1024', [], null, null, true);
    }

    public function testCreateInvalidDkimKeySizeException()
    {
        $this->expectException(InvalidArgumentException::class);

        $api = $this->getApiInstance();
        $api->create('example.com', null, null, null, null, null, null, 'http', '4096');
    }

    public function testCreateInvalidWebSchemeException()
    {
        $this->expectException(InvalidArgumentException::class);

        $api = $this->getApiInstance();
        $api->create('example.com', null, null, null, null, null, null, 'ftp');
    }

    public function testDelete()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v4/domains/example.com');
        $this->setHydrateClass(DeleteResponse::class);

        $api = $this->getApiInstance();
        $api->delete('example.com');
    }

    public function testCredentials()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/domains/example.com/credentials?limit=100&skip=0');
        $this->setHydrateClass(CredentialResponse::class);

        $api = $this->getApiInstance();
        $api->credentials('example.com');
    }

    public function testCredentialsWithCustomParams()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/domains/example.com/credentials?limit=50&skip=10');
        $this->setHydrateClass(CredentialResponse::class);

        $api = $this->getApiInstance();
        $api->credentials('example.com', 50, 10);
    }

    public function testCreateCredential()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/domains/example.com/credentials');
        $this->setRequestBody(
            [
                'login' => 'testuser',
                'password' => 'password123',
            ]
        );
        $this->setHydrateClass(CreateCredentialResponse::class);

        $api = $this->getApiInstance();
        $api->createCredential('example.com', 'testuser', 'password123');
    }

    public function testCreateCredentialPasswordTooShortException()
    {
        $this->expectException(InvalidArgumentException::class);

        $api = $this->getApiInstance();
        $api->createCredential('example.com', 'testuser', '1234');
    }

    public function testCreateCredentialPasswordTooLongException()
    {
        $this->expectException(InvalidArgumentException::class);

        $api = $this->getApiInstance();
        $api->createCredential('example.com', 'testuser', str_repeat('a', 33));
    }

    public function testUpdateCredential()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/credentials/testuser');
        $this->setRequestBody(
            [
                'password' => 'newpassword',
            ]
        );
        $this->setHydrateClass(UpdateCredentialResponse::class);

        $api = $this->getApiInstance();
        $api->updateCredential('example.com', 'testuser', 'newpassword');
    }

    public function testUpdateCredentialPasswordTooShortException()
    {
        $this->expectException(InvalidArgumentException::class);

        $api = $this->getApiInstance();
        $api->updateCredential('example.com', 'testuser', '1234');
    }

    public function testDeleteCredential()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/domains/example.com/credentials/testuser');
        $this->setHydrateClass(DeleteCredentialResponse::class);

        $api = $this->getApiInstance();
        $api->deleteCredential('example.com', 'testuser');
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
        $this->setRequestBody(
            [
                'require_tls' => 'true',
                'skip_verification' => 'false',
            ]
        );
        $this->setHydrateClass(UpdateConnectionResponse::class);

        $api = $this->getApiInstance();
        $api->updateConnection('example.com', true, false);
    }

    public function testUpdateConnectionOnlyRequireTLS()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/connection');
        $this->setRequestBody(
            [
                'require_tls' => 'false',
            ]
        );
        $this->setHydrateClass(UpdateConnectionResponse::class);

        $api = $this->getApiInstance();
        $api->updateConnection('example.com', false, null);
    }

    public function testUpdateConnectionOnlySkipVerification()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/connection');
        $this->setRequestBody(
            [
                'skip_verification' => 'true',
            ]
        );
        $this->setHydrateClass(UpdateConnectionResponse::class);

        $api = $this->getApiInstance();
        $api->updateConnection('example.com', null, true);
    }

    public function testUpdateWebScheme()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v4/domains/example.com');
        $this->setRequestBody(
            [
                'web_scheme' => 'https',
            ]
        );
        $this->setHydrateClass(WebSchemeResponse::class);

        $api = $this->getApiInstance();
        $api->updateWebScheme('example.com', 'https');
    }

    public function testUpdateWebSchemeInvalidException()
    {
        $this->expectException(InvalidArgumentException::class);

        $api = $this->getApiInstance();
        $api->updateWebScheme('example.com', 'ftp');
    }

    public function testUpdateUseAutomaticSenderSecurity()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v4/domains/example.com');
        $this->setRequestBody(
            [
                'use_automatic_sender_security' => true,
            ]
        );
        $this->setHydrateClass(UseAutomaticSenderSecurity::class);

        $api = $this->getApiInstance();
        $api->updateUseAutomaticSenderSecurity('example.com', true);
    }

    public function testUpdateUseAutomaticSenderSecurityFalse()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v4/domains/example.com');
        $this->setRequestBody(
            [
                'use_automatic_sender_security' => false,
            ]
        );
        $this->setHydrateClass(UseAutomaticSenderSecurity::class);

        $api = $this->getApiInstance();
        $api->updateUseAutomaticSenderSecurity('example.com', false);
    }

    public function testVerify()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v4/domains/example.com/verify');
        $this->setHydrateClass(VerifyResponse::class);

        $api = $this->getApiInstance();
        $api->verify('example.com');
    }

    public function testTracking()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/domains/example.com/tracking');
        $this->setHydrateClass(TrackingResponse::class);

        $api = $this->getApiInstance();
        $api->tracking('example.com');
    }

    public function updateClickTrackingDataProvider(): array
    {
        return [
            ['yes'],
            ['no'],
            ['htmlonly'],
        ];
    }

    /**
     * @dataProvider updateClickTrackingDataProvider
     */
    public function testUpdateClickTracking(string $active)
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/tracking/click');
        $this->setRequestBody(
            [
                'active' => $active,
            ]
        );
        $this->setHydrateClass(UpdateClickTrackingResponse::class);

        /**
         * @var DomainV4
         */
        $api = $this->getApiInstance();
        $api->updateClickTracking('example.com', $active);
    }

    public function testUpdateClickTrackingException()
    {
        $this->expectException(InvalidArgumentException::class);

        /**
         * @var DomainV4
         */
        $api = $this->getApiInstance();
        $api->updateClickTracking('example.com', 'invalid');
    }

    public function updateOpenTrackingDataProvider(): array
    {
        return [
            ['yes'],
            ['no'],
        ];
    }

    /**
     * @dataProvider updateOpenTrackingDataProvider
     */
    public function testUpdateOpenTracking(string $active)
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/tracking/open');
        $this->setRequestBody(
            [
                'active' => $active,
            ]
        );
        $this->setHydrateClass(UpdateOpenTrackingResponse::class);

        /**
         * @var DomainV4
         */
        $api = $this->getApiInstance();
        $api->updateOpenTracking('example.com', $active);
    }

    public function testUpdateOpenTrackingException()
    {
        $this->expectException(InvalidArgumentException::class);

        /**
         * @var DomainV4
         */
        $api = $this->getApiInstance();
        $api->updateOpenTracking('example.com', 'invalid');
    }

    public function unsubscribeDataProvider(): array
    {
        return [
            ['yes', '<b>Unsubscribe</b>', 'Unsubscribe'],
            ['no', '<s>Test</s>', 'Test'],
            ['true', '<i>Footer</i>', 'Footer Text'],
            ['false', '<u>HTML</u>', 'Plain Text'],
        ];
    }

    /**
     * @dataProvider unsubscribeDataProvider
     */
    public function testUpdateUnsubscribeTracking(string $active, string $htmlFooter, string $textFooter)
    {
        $expectedActive = (in_array($active, ['yes', 'true'], true)) ? 'true' : 'false';

        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/tracking/unsubscribe');
        $this->setRequestBody(
            [
                'active' => $expectedActive,
                'html_footer' => $htmlFooter,
                'text_footer' => $textFooter,
            ]
        );
        $this->setHydrateClass(UpdateUnsubscribeTrackingResponse::class);

        /**
         * @var DomainV4
         */
        $api = $this->getApiInstance();
        $api->updateUnsubscribeTracking('example.com', $active, $htmlFooter, $textFooter);
    }

    public function testUpdateUnsubscribeTrackingException()
    {
        $this->expectException(InvalidArgumentException::class);

        /**
         * @var DomainV4
         */
        $api = $this->getApiInstance();
        $api->updateUnsubscribeTracking('example.com', 'invalid', 'html-footer', 'text-footer');
    }

    public function testUpdateWebPrefix()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/domains/example.com/web_prefix');
        $this->setRequestBody(
            [
                'web_prefix' => 'tracking',
            ]
        );
        $this->setHydrateClass(WebPrefixResponse::class);

        /**
         * @var DomainV4
         */
        $api = $this->getApiInstance();
        $api->updateWebPrefix('example.com', 'tracking');
    }
}

