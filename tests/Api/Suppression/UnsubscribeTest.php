<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\Suppression\Unsubscribe;
use Mailgun\Model\Suppression\Unsubscribe\CreateResponse;
use Mailgun\Model\Suppression\Unsubscribe\DeleteResponse;
use Mailgun\Model\Suppression\Unsubscribe\IndexResponse;
use Mailgun\Model\Suppression\Unsubscribe\ShowResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class UnsubscribeTest extends TestCase
{
    public function testIndex()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/example.com/unsubscribes?limit=100');
        $this->setHydrateClass(IndexResponse::class);

        $api = $this->getApiInstance();
        $api->index('example.com');
    }

    public function testShow()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/example.com/unsubscribes/foo@bar.com');
        $this->setHydrateClass(ShowResponse::class);

        $api = $this->getApiInstance();
        $api->show('example.com', 'foo@bar.com');
    }

    public function testCreate()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/example.com/unsubscribes');
        $this->setHydrateClass(CreateResponse::class);
        $this->setRequestBody(
            [
            'address' => 'foo@bar.com',
            ]
        );

        $api = $this->getApiInstance();
        $api->create('example.com', 'foo@bar.com');
    }

    public function testDelete()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/example.com/unsubscribes/foo@bar.com');
        $this->setHydrateClass(DeleteResponse::class);

        $api = $this->getApiInstance();
        $api->delete('example.com', 'foo@bar.com');
    }

    public function testDeleteWithTag()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/example.com/unsubscribes/foo@bar.com');
        $this->setRequestBody(['tag' => 'tag1']);
        $this->setHydrateClass(DeleteResponse::class);

        $api = $this->getApiInstance();
        $api->delete('example.com', 'foo@bar.com', 'tag1');
    }

    public function testDeleteAll()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/example.com/unsubscribes');
        $this->setHydrateClass(DeleteResponse::class);

        $api = $this->getApiInstance();
        $api->deleteAll('example.com');
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiClass()
    {
        return Unsubscribe::class;
    }
}
