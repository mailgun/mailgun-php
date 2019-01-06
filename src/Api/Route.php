<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Model\Route\Response\CreateResponse;
use Mailgun\Model\Route\Response\DeleteResponse;
use Mailgun\Model\Route\Response\IndexResponse;
use Mailgun\Model\Route\Response\ShowResponse;
use Mailgun\Model\Route\Response\UpdateResponse;

/**
 * {@link https://documentation.mailgun.com/api-routes.html}.
 *
 * @author David Garcia <me@davidgarcia.cat>
 */
class Route extends HttpApi
{
    /**
     * Fetches the list of Routes.
     *
     * @param int $limit Maximum number of records to return. (100 by default)
     * @param int $skip  Number of records to skip. (0 by default)
     *
     * @return IndexResponse
     */
    public function index($limit = 100, $skip = 0)
    {
        Assert::integer($limit);
        Assert::integer($skip);
        Assert::greaterThan($limit, 0);
        Assert::greaterThanEq($skip, 0);

        $params = [
            'limit' => $limit,
            'skip' => $skip,
        ];

        $response = $this->httpGet('/v3/routes', $params);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a single Route object based on its ID.
     *
     * @param string $routeId Route ID returned by the Routes::index() method
     *
     * @return ShowResponse
     */
    public function show($routeId)
    {
        Assert::stringNotEmpty($routeId);

        $response = $this->httpGet(sprintf('/v3/routes/%s', $routeId));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Creates a new Route.
     *
     * @param string $expression  A filter expression like "match_recipient('.*@gmail.com')"
     * @param array  $actions     Route action. This action is executed when the expression evaluates to True. Example: "forward('alice@example.com')"
     * @param string $description An arbitrary string
     * @param int    $priority    Integer: smaller number indicates higher priority. Higher priority routes are handled first. Defaults to 0.
     *
     * @return CreateResponse
     */
    public function create($expression, array $actions, $description, $priority = 0)
    {
        Assert::string($expression);
        Assert::isArray($actions);
        Assert::string($description);
        Assert::integer($priority);

        $params = [
            'priority' => $priority,
            'expression' => $expression,
            'action' => $actions,
            'description' => $description,
        ];

        $response = $this->httpPost('/v3/routes', $params);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * Updates a given Route by ID. All parameters are optional.
     * This API call only updates the specified fields leaving others unchanged.
     *
     * @param string      $routeId     Route ID returned by the Routes::index() method
     * @param string|null $expression  A filter expression like "match_recipient('.*@gmail.com')"
     * @param array|null  $actions     Route action. This action is executed when the expression evaluates to True. Example: "forward('alice@example.com')"
     * @param string|null $description An arbitrary string
     * @param int|null    $priority    Integer: smaller number indicates higher priority. Higher priority routes are handled first. Defaults to 0.
     *
     * @return UpdateResponse
     */
    public function update($routeId, $expression = null, array $actions = [], $description = null, $priority = null)
    {
        Assert::stringNotEmpty($routeId);
        Assert::nullOrString($expression);
        Assert::isArray($actions);
        Assert::nullOrString($description);
        Assert::nullOrInteger($priority);

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
            $params['priority'] = $priority;
        }

        $response = $this->httpPut(sprintf('/v3/routes/%s', $routeId), $params);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Deletes a Route based on the ID.
     *
     * @param string $routeId Route ID returned by the Routes::index() method
     *
     * @return DeleteResponse
     */
    public function delete($routeId)
    {
        Assert::stringNotEmpty($routeId);

        $response = $this->httpDelete(sprintf('/v3/routes/%s', $routeId));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }
}
