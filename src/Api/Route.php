<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Model\Route\CreateResponse;
use Mailgun\Model\Route\DeleteResponse;
use Mailgun\Model\Route\IndexResponse;
use Mailgun\Model\Route\MatchRouteResponse;
use Mailgun\Model\Route\ShowResponse;
use Mailgun\Model\Route\UpdateResponse;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-routes.html
 *
 * @author David Garcia <me@davidgarcia.cat>
 */
class Route extends HttpApi
{
    /**
     * Fetches the list of Routes.
     * @param  int                      $limit          Maximum number of records to return. (100 by default)
     * @param  int                      $skip           Number of records to skip. (0 by default)
     * @param  array                    $requestHeaders
     * @return IndexResponse
     * @throws ClientExceptionInterface
     */
    public function index(int $limit = 100, int $skip = 0, array $requestHeaders = [])
    {
        Assert::greaterThan($limit, 0);
        Assert::greaterThanEq($skip, 0);
        Assert::range($limit, 1, 1000);

        $params = [
            'limit' => $limit,
            'skip' => $skip,
        ];

        $response = $this->httpGet('/v3/routes', $params, $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a single Route object based on its ID.
     * @param  string                   $routeId        Route ID returned by the Routes::index() method
     * @param  array                    $requestHeaders
     * @return ShowResponse
     * @throws ClientExceptionInterface
     */
    public function show(string $routeId, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($routeId);

        $response = $this->httpGet(sprintf('/v3/routes/%s', $routeId), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Creates a new Route.
     * @param  string                   $expression     A filter expression like "match_recipient('.*@gmail.com')"
     * @param  array                    $actions        Route action. This action is executed when the expression evaluates to True. Example: "forward('alice@example.com')"
     * @param  string                   $description    An arbitrary string
     * @param  int                      $priority       Integer: smaller number indicates higher priority. Higher priority routes are handled first. Defaults to 0.
     * @param  array                    $requestHeaders
     * @return CreateResponse
     * @throws ClientExceptionInterface
     */
    public function create(string $expression, array $actions, string $description, int $priority = 0, array $requestHeaders = [])
    {
        Assert::isArray($actions);

        $params = [
            'priority' => (string) $priority,
            'expression' => $expression,
            'action' => $actions,
            'description' => $description,
        ];

        $response = $this->httpPost('/v3/routes', $params, $requestHeaders);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * Updates a given Route by ID. All parameters are optional.
     * This API call only updates the specified fields leaving others unchanged.
     * @param  string                   $routeId        Route ID returned by the Routes::index() method
     * @param  string|null              $expression     A filter expression like "match_recipient('.*@gmail.com')"
     * @param  array                    $actions        Route action. This action is executed when the expression evaluates to True. Example: "forward('alice@example.com')"
     * @param  string|null              $description    An arbitrary string
     * @param  int|null                 $priority       Integer: smaller number indicates higher priority. Higher priority routes are handled first. Defaults to 0.
     * @param  array                    $requestHeaders
     * @return UpdateResponse
     * @throws ClientExceptionInterface
     */
    public function update(
        string $routeId,
        ?string $expression = null,
        array $actions = [],
        ?string $description = null,
        ?int $priority = null,
        array $requestHeaders = []
    ) {
        Assert::stringNotEmpty($routeId);
        $params = [];

        if (!empty($expression)) {
            $params['expression'] = trim($expression);
        }

        foreach ($actions as $action) {
            Assert::stringNotEmpty($action);

            $params['action'] = isset($params['action']) ? $params['action'] : [];
            $params['action'][] = $action;
        }

        if (!empty($description)) {
            $params['description'] = trim($description);
        }

        if (!empty($priority)) {
            $params['priority'] = (string) $priority;
        }

        $response = $this->httpPut(sprintf('/v3/routes/%s', $routeId), $params, $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Deletes a Route based on the ID.
     * @param  string                   $routeId        Route ID returned by the Routes::index() method
     * @param  array                    $requestHeaders
     * @return DeleteResponse
     * @throws ClientExceptionInterface
     */
    public function delete(string $routeId, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($routeId);

        $response = $this->httpDelete(sprintf('/v3/routes/%s', $routeId), [], $requestHeaders);

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * Match address to route
     * @param string $address
     * @param array $requestHeaders
     * @return MatchRouteResponse
     * @throws ClientExceptionInterface
     */
    public function matchAddressToRoute(string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($address);

        $query = [
            'address' => $address,
        ];

        $response = $this->httpGet('/v3/routes/match', $query, $requestHeaders);

        return $this->hydrateResponse($response, MatchRouteResponse::class);
    }
}
