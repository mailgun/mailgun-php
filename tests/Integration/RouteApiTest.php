<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Integration;

use Mailgun\Model\Route\Response\CreateResponse;
use Mailgun\Model\Route\Response\IndexResponse;
use Mailgun\Model\Route\Response\DeleteResponse;
use Mailgun\Model\Route\Response\ShowResponse;
use Mailgun\Model\Route\Response\UpdateResponse;
use Mailgun\Model\Route\Action;
use Mailgun\Model\Route\Route;
use Mailgun\Tests\Api\TestCase;

class RouteApiTest extends TestCase
{
    protected function getApiClass()
    {
        return 'Mailgun\Api\Route';
    }

    public function testRouteCreate()
    {
        $mg = $this->getMailgunClient();

        $response = $mg->routes()->create(
            'catch_all()',
            ['forward("test@example.tld")', 'stop()'],
            'test-route',
            100
        );

        $this->assertInstanceOf(CreateResponse::class, $response);
        $this->assertInstanceOf(Route::class, $response->getRoute());
        $this->assertSame('catch_all()', $response->getRoute()->getFilter());
        $this->assertCount(2, $response->getRoute()->getActions());

        return $response->getRoute()->getId();
    }

    /**
     * @expectedException \Mailgun\Exception\HttpClientException
     * @expectedExceptionCode 400
     */
    public function testRouteCreateInvalidFilter()
    {
        $mg = $this->getMailgunClient();

        $mg->routes()->create(
            'invalid_function()',
            ['stop()'],
            ''
        );
    }

    /**
     * @depends testRouteCreate
     */
    public function testRouteShow($routeId)
    {
        $mg = $this->getMailgunClient();

        $response = $mg->routes()->show($routeId);

        $this->assertInstanceOf(ShowResponse::class, $response);
        $this->assertInstanceOf(Route::class, $response->getRoute());
        $this->assertSame('test-route', $response->getRoute()->getDescription());
        $this->assertCount(2, $response->getRoute()->getActions());
        $this->assertContainsOnlyInstancesOf(Action::class, $response->getRoute()->getActions());
        $this->assertSame('forward("test@example.tld")', $response->getRoute()->getActions()[0]->getAction());

        return $routeId;
    }

    /**
     * @depends testRouteShow
     */
    public function testRouteUpdate($routeId)
    {
        $mg = $this->getMailgunClient();

        $response = $mg->routes()->update(
            $routeId,
            'match_recipient("foo@bar.com")',
            ['stop()'],
            'test-route-updated',
            200
        );

        $this->assertInstanceOf(UpdateResponse::class, $response);
        $this->assertInstanceOf(Route::class, $response->getRoute());
        $this->assertSame('test-route-updated', $response->getRoute()->getDescription());

        return $routeId;
    }

    /**
     * @depends testRouteUpdate
     */
    public function testRouteIndex($routeId)
    {
        $mg = $this->getMailgunClient();

        $response = $mg->routes()->index();

        $this->assertInstanceOf(IndexResponse::class, $response);
        $this->assertContainsOnlyInstancesOf(Route::class, $response->getRoutes());
        $foundTestRoute = false;
        foreach ($response->getRoutes() as $route) {
            if ($route->getId() === $routeId && 'test-route-updated' === $route->getDescription()) {
                $foundTestRoute = true;
            }
        }
        $this->assertTrue($foundTestRoute);

        return $routeId;
    }

    /**
     * @depends testRouteIndex
     */
    public function testRouteDelete($routeId)
    {
        $mg = $this->getMailgunClient();

        $response = $mg->routes()->delete($routeId);

        $this->assertInstanceOf(DeleteResponse::class, $response);
        $this->assertSame('Route has been deleted', $response->getMessage());
    }

    /**
     * @expectedException \Mailgun\Exception\HttpClientException
     * @expectedExceptionCode 404
     */
    public function testRouteDeleteInvalid()
    {
        $mg = $this->getMailgunClient();

        $mg->routes()->delete('000000000000000000000000');
    }
}
