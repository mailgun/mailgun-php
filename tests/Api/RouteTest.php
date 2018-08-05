<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use GuzzleHttp\Psr7\Response;
use Mailgun\Api\Route;
use Mailgun\Model\Route\Response\DeleteResponse;
use Mailgun\Model\Route\Response\IndexResponse;
use Mailgun\Model\Route\Response\ShowResponse;
use Mailgun\Model\Route\Response\UpdateResponse;

/**
 * @author David Garcia <me@davidgarcia.cat>
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class RouteTest extends TestCase
{
    public function testIndex()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/routes?limit=100&skip=0');
        $this->setHydrateClass(IndexResponse::class);

        $api = $this->getApiInstance();
        $api->index();
    }

    public function testShow()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/routes/4711');
        $this->setHydrateClass(ShowResponse::class);

        $api = $this->getApiInstance();
        $api->show('4711');
    }

    public function testCreate()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPost')
            ->willReturn(new Response());

        $api->create('catch_all()', ['forward("mailbox@myapp.com")'], 'example', 100);
    }


    public function testUpdate()
    {
        $this->setRequestMethod('PUT');
        $this->setRequestUri('/v3/routes/4711');
        $this->setHydrateClass(UpdateResponse::class);
        $this->setRequestBody([
            'expression' => 'catch_all()',
           'action' => 'forward("mailbox@myapp.com")',
           'description' => 'example',
           'priority' => 100,
        ]);

        $api = $this->getApiInstance();
        $api->update('4711', 'catch_all()', ['forward("mailbox@myapp.com")'], 'example', 100);
    }

    public function testDelete()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/routes/4711');
        $this->setHydrateClass(DeleteResponse::class);

        $api = $this->getApiInstance();
        $api->delete('4711');
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiClass()
    {
        return Route::class;
    }
}
