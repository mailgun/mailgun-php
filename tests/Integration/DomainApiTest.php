<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Mailgun\Tests\Integration;

use Mailgun\Api\Domain;
use Mailgun\Resource\Api\ErrorResponse;
use Mailgun\Resource\Api\Domain\CreateCredentialResponse;
use Mailgun\Resource\Api\Domain\DeleteCredentialResponse;
use Mailgun\Resource\Api\Domain\DeleteResponse;
use Mailgun\Resource\Api\Domain\Domain as DomainObject;
use Mailgun\Resource\Api\Domain\CredentialResponseItem;
use Mailgun\Resource\Api\Domain\CredentialResponse;
use Mailgun\Resource\Api\Domain\ConnectionResponse;
use Mailgun\Resource\Api\Domain\UpdateConnectionResponse;
use Mailgun\Resource\Api\Domain\UpdateCredentialResponse;
use Mailgun\Tests\Api\TestCase;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class DomainApiTest extends TestCase
{
    protected function getApiClass()
    {
        return 'Mailgun\Api\Domain';
    }

    /**
     * Performs `GET /v3/domains` and ensures $this->testDomain exists
     * in the returned list.
     */
    public function testIndex()
    {
        $mg = $this->getMailgunClient();

        $domainList = $mg->getDomainApi()->index();
        $found = false;
        foreach ($domainList->getDomains() as $domain) {
            if ($domain->getName() === $this->testDomain) {
                $found = true;
            }
        }

        $this->assertContainsOnlyInstancesOf(DomainObject::class, $domainList->getDomains());
        $this->assertTrue($found);
    }

    /**
     * Performs `GET /v3/domains/<domain>` and ensures $this->testDomain
     * is properly returned.
     */
    public function testDomainGet()
    {
        $mg = $this->getMailgunClient();

        $domain = $mg->getDomainApi()->show($this->testDomain);
        $this->assertNotNull($domain);
        $this->assertNotNull($domain->getDomain());
        $this->assertNotNull($domain->getInboundDNSRecords());
        $this->assertNotNull($domain->getOutboundDNSRecords());
        $this->assertEquals($domain->getDomain()->getState(), 'active');
    }

    /**
     * Performs `DELETE /v3/domains/<domain>` on a non-existent domain.
     */
    public function testRemoveDomainNoExist()
    {
        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->delete('example.notareal.tld');
        $this->assertNotNull($ret);
        $this->assertInstanceOf(DeleteResponse::class, $ret);
        $this->assertEquals('Domain not found', $ret->getMessage());
    }

    /**
     * Performs `POST /v3/domains` to attempt to create a domain with valid
     * values.
     */
    public function testDomainCreate()
    {
        $mg = $this->getMailgunClient();

        $domain = $mg->getDomainApi()->create(
            'example.notareal.tld',     // domain name
            'exampleOrgSmtpPassword12', // smtp password
            'tag',                      // default spam action
            false                       // wildcard domain?
        );
        $this->assertNotNull($domain);
        $this->assertNotNull($domain->getDomain());
        $this->assertNotNull($domain->getInboundDNSRecords());
        $this->assertNotNull($domain->getOutboundDNSRecords());
    }

    /**
     * Performs `POST /v3/domains` to attempt to create a domain with duplicate
     * values.
     */
    public function testDomainCreateDuplicateValues()
    {
        $mg = $this->getMailgunClient();

        $domain = $mg->getDomainApi()->create(
            'example.notareal.tld',     // domain name
            'exampleOrgSmtpPassword12', // smtp password
            'tag',                      // default spam action
            false                       // wildcard domain?
        );
        $this->assertNotNull($domain);
        $this->assertInstanceOf(ErrorResponse::class, $domain);
        $this->assertEquals('This domain name is already taken', $domain->getMessage());
    }

    /**
     * Performs `DELETE /v3/domains/<domain>` to remove a domain from the account.
     */
    public function testRemoveDomain()
    {
        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->delete('example.notareal.tld');
        $this->assertNotNull($ret);
        $this->assertInstanceOf(DeleteResponse::class, $ret);
        $this->assertEquals('Domain has been deleted', $ret->getMessage());
    }

    /**
     * Performs `POST /v3/domains/<domain>/credentials` to add a credential pair
     * to the domain.
     */
    public function testCreateCredential()
    {
        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->createCredential(
            $this->testDomain,
            'user-test@'.$this->testDomain,
            'Password.01!'
        );
        $this->assertNotNull($ret);
        $this->assertInstanceOf(CreateCredentialResponse::class, $ret);
        $this->assertEquals('Created 1 credentials pair(s)', $ret->getMessage());
    }

    /**
     * Performs `POST /v3/domains/<domain>/credentials` to attempt to add an invalid
     * credential pair.
     *
     * @expectedException InvalidArgumentException
     */
    public function testCreateCredentialBadPasswordLong()
    {
        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->createCredential(
            $this->testDomain,
            'user-test',
            'ExtremelyLongPasswordThatCertainlyWillNotBeAccepted'
        );
        $this->assertNotNull($ret);
        $this->assertInstanceOf(CreateCredentialResponse::class, $ret);
    }

    /**
     * Performs `POST /v3/domains/<domain>/credentials` to attempt to add an invalid
     * credential pair.
     *
     * @expectedException InvalidArgumentException
     */
    public function testCreateCredentialBadPasswordShort()
    {
        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->createCredential(
            $this->testDomain,
            'user-test',
            'no'
        );
        $this->assertNotNull($ret);
        $this->assertInstanceOf(CreateCredentialResponse::class, $ret);
    }

    /**
     * Performs `GET /v3/domains/<domain>/credentials` to get a list of active credentials.
     */
    public function testListCredentials()
    {
        $mg = $this->getMailgunClient();

        $found = false;

        $ret = $mg->getDomainApi()->credentials($this->testDomain);
        $this->assertNotNull($ret);
        $this->assertInstanceOf(CredentialResponse::class, $ret);
        $this->assertContainsOnlyInstancesOf(CredentialResponseItem::class, $ret->getCredentials());

        foreach ($ret->getCredentials() as $cred) {
            if ($cred->getLogin() === 'user-test@'.$this->testDomain) {
                $found = true;
            }
        }

        $this->assertTrue($found);
    }

    /**
     * Performs `GET /v3/domains/<domain>/credentials` on a non-existent domain.
     */
    public function testListCredentialsBadDomain()
    {
        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->credentials('mailgun.org');
        $this->assertNotNull($ret);
        $this->assertInstanceOf(ErrorResponse::class, $ret);
        $this->assertEquals('Domain not found: mailgun.org', $ret->getMessage());
    }

    /**
     * Performs `PUT /v3/domains/<domain>/credentials/<login>` to update a credential's
     * password.
     */
    public function testUpdateCredential()
    {
        $login = 'user-test@'.$this->testDomain;

        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->updateCredential(
            $this->testDomain,
            $login,
            'Password..02!'
        );
        $this->assertNotNull($ret);
        $this->assertInstanceOf(UpdateCredentialResponse::class, $ret);
        $this->assertEquals('Password changed', $ret->getMessage());
    }

    /**
     * Performs `PUT /v3/domains/<domain>/credentials/<login>` with a bad password.
     *
     * @expectedException InvalidArgumentException
     */
    public function testUpdateCredentialBadPasswordLong()
    {
        $login = 'user-test@'.$this->testDomain;

        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->updateCredential(
            $this->testDomain,
            $login,
            'ThisIsAnExtremelyLongPasswordThatSurelyWontBeAccepted'
        );
        $this->assertNotNull($ret);
    }

    /**
     * Performs `PUT /v3/domains/<domain>/credentials/<login>` with a bad password.
     *
     * @expectedException InvalidArgumentException
     */
    public function testUpdateCredentialBadPasswordShort()
    {
        $login = 'user-test@'.$this->testDomain;

        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->updateCredential(
            $this->testDomain,
            $login,
            'no'
        );
        $this->assertNotNull($ret);
    }

    /**
     * Performs `DELETE /v3/domains/<domain>/credentials/<login>` to remove a credential
     * pair from a domain.
     */
    public function testRemoveCredential()
    {
        $login = 'user-test@'.$this->testDomain;

        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->deleteCredential(
            $this->testDomain,
            $login
        );
        $this->assertNotNull($ret);
        $this->assertInstanceOf(DeleteCredentialResponse::class, $ret);
        $this->assertEquals('Credentials have been deleted', $ret->getMessage());
        $this->assertEquals($login, $ret->getSpec());
    }

    /**
     * Performs `DELETE /v3/domains/<domain>/credentials/<login>` to remove an invalid
     * credential pair from a domain.
     */
    public function testRemoveCredentialNoExist()
    {
        $login = 'user-noexist-test@'.$this->testDomain;

        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->deleteCredential(
            $this->testDomain,
            $login
        );
        $this->assertNotNull($ret);
        $this->assertInstanceOf(DeleteCredentialResponse::class, $ret);
        $this->assertEquals('Credentials not found', $ret->getMessage());
    }

    /**
     * Performs `GET /v3/domains/<domain>/connection` to retrieve connection settings.
     */
    public function testGetDeliverySettings()
    {
        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->connection($this->testDomain);
        $this->assertNotNull($ret);
        $this->assertInstanceOf(ConnectionResponse::class, $ret);
        $this->assertTrue(is_bool($ret->getSkipVerification()));
        $this->assertTrue(is_bool($ret->getRequireTLS()));
    }

    /**
     * Performs `PUT /v3/domains/<domain>/connection` to set connection settings.
     */
    public function testSetDeliverySettings()
    {
        $mg = $this->getMailgunClient();

        $ret = $mg->getDomainApi()->updateConnection(
            $this->testDomain,
            true,
            false
        );
        $this->assertNotNull($ret);
        $this->assertInstanceOf(UpdateConnectionResponse::class, $ret);
        $this->assertEquals('Domain connection settings have been updated, may take 10 minutes to fully propagate', $ret->getMessage());
        $this->assertEquals(true, $ret->getRequireTLS());
        $this->assertEquals(false, $ret->getSkipVerification());
    }
}
