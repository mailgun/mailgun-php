<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\Suppression\Whitelist;
use Mailgun\Model\Suppression\Whitelist\CreateResponse;
use Mailgun\Model\Suppression\Whitelist\DeleteAllResponse;
use Mailgun\Model\Suppression\Whitelist\DeleteResponse;
use Mailgun\Model\Suppression\Whitelist\ImportResponse;
use Mailgun\Model\Suppression\Whitelist\IndexResponse;
use Mailgun\Model\Suppression\Whitelist\ShowResponse;

/**
 * @author Artem Bondarenko <artem@uartema.com>
 */
class WhitelistTest extends TestCase
{
    public function testIndex()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/example.com/whitelists?limit=25');
        $this->setHydrateClass(IndexResponse::class);

        $api = $this->getApiInstance();
        $api->index('example.com', 25);
    }

    public function testShow()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/example.com/whitelists/foo@bar.com');
        $this->setHydrateClass(ShowResponse::class);

        $api = $this->getApiInstance();
        $api->show('example.com', 'foo@bar.com');
    }

    public function testCreateDomain()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/example.com/whitelists');
        $this->setHydrateClass(CreateResponse::class);
        $this->setRequestBody([
            'address' => 'foo@bar.com',
        ]);

        $api = $this->getApiInstance();
        $api->create('example.com', 'foo@bar.com');
    }

    public function testCreateEmail()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/example.com/whitelists');
        $this->setHydrateClass(CreateResponse::class);
        $this->setRequestBody([
            'domain' => 'foobar.com',
        ]);

        $api = $this->getApiInstance();
        $api->create('example.com', 'foobar.com');
    }

    public function testCreateNonValidDomainOrAddress()
    {
        $this->expectException(\InvalidArgumentException::class);

        $api = $this->getApiInstance();
        $api->create('example.com', '_123');
    }

    public function testImport()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/example.com/whitelists/import');
        $this->setHydrateClass(ImportResponse::class);
        $this->setRequestBody([
            'file' => 'resource',
        ]);
        $this->setRequestHeaders([
            'filename' => basename(__FILE__),
        ]);

        $api = $this->getApiInstance();
        $api->import('example.com', __FILE__);
    }

    public function testDelete()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/example.com/whitelists/foo@bar.com');
        $this->setHydrateClass(DeleteResponse::class);

        $api = $this->getApiInstance();
        $api->delete('example.com', 'foo@bar.com');
    }

    public function testDeleteDomain()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/example.com/whitelists/foobar.com');
        $this->setHydrateClass(DeleteResponse::class);

        $api = $this->getApiInstance();
        $api->delete('example.com', 'foobar.com');
    }

    public function testDeleteAll()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/example.com/whitelists');
        $this->setHydrateClass(DeleteAllResponse::class);

        $api = $this->getApiInstance();
        $api->deleteAll('example.com');
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiClass()
    {
        return Whitelist::class;
    }
}
