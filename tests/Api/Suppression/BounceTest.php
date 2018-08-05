<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\Suppression\Bounce;
use Mailgun\Model\Suppression\Bounce\CreateResponse;
use Mailgun\Model\Suppression\Bounce\DeleteResponse;
use Mailgun\Model\Suppression\Bounce\IndexResponse;
use Mailgun\Model\Suppression\Bounce\ShowResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class BounceTest extends TestCase
{
    public function testIndex()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/example.com/bounces?limit=100');
        $this->setHydrateClass(IndexResponse::class);

        $api = $this->getApiInstance();
        $api->index('example.com');
    }

    public function testShow()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/example.com/bounces/foo@bar.com');
        $this->setHydrateClass(ShowResponse::class);

        $api = $this->getApiInstance();
        $api->show('example.com', 'foo@bar.com');
    }

    public function testCreate()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/example.com/bounces');
        $this->setHydrateClass(CreateResponse::class);
        $this->setRequestBody([
            'address' => 'foo@bar.com',
            'foo' => 'xxx',
        ]);

        $api = $this->getApiInstance();
        $api->create('example.com', 'foo@bar.com', ['foo' => 'xxx']);
    }

    public function testDelete()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/example.com/bounces/foo@bar.com');
        $this->setHydrateClass(DeleteResponse::class);

        $api = $this->getApiInstance();
        $api->delete('example.com', 'foo@bar.com');
    }

    public function testDeleteAll()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/example.com/bounces');
        $this->setHydrateClass(DeleteResponse::class);

        $api = $this->getApiInstance();
        $api->deleteAll('example.com');
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiClass()
    {
        return Bounce::class;
    }
}
