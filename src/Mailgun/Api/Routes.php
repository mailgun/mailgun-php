<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Resource\Api\Routes\Response\IndexResponse;

/**
 * {@link https://documentation.mailgun.com/api-routes.html}.
 *
 * @author David Garcia <me@davidgarcia.cat>
 */
class Routes extends HttpApi
{
    /**
     * Fetches the list of Routes. Note that Routes are defined globally,
     * per account, not per domain as most of other API calls.
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

        $params = [
            'limit' => $limit,
            'skip' => $skip,
        ];

        $response = $this->httpGet('/v3/routes', $params);

        return $this->safeDeserialize($response, IndexResponse::class);
    }

    /**
     * Returns a single Route object based on its ID.
     *
     * @param string $routeId Route ID returned by the Routes::index() method
     *
     * @return
     */
    public function show($routeId)
    {
    }

    /**
     * Creates a new Route.
     *
     * @param string $expression  A filter expression like "match_recipient('.*@gmail.com')"
     * @param array  $actions     Route action. This action is executed when the expression evaluates to True. Example: "forward('alice@example.com')"
     * @param string $description An arbitrary string
     * @param int    $priority    Integer: smaller number indicates higher priority. Higher priority routes are handled first. Defaults to 0.
     *
     * @return
     */
    public function create($expression, array $actions, $description, $priority = 0)
    {
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
     * @return
     */
    public function update($routeId, $expression = null, array $actions = null, $description = null, $priority = null)
    {
    }

    /**
     * Deletes a Route based on the ID.
     */
    public function delete()
    {
    }
}
