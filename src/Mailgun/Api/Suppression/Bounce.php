<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api\Suppression;

use Mailgun\Api\HttpApi;
use Mailgun\Api\Pagination;
use Mailgun\Assert;
use Mailgun\Model\Suppression\Bounce\CreateResponse;
use Mailgun\Model\Suppression\Bounce\DeleteResponse;
use Mailgun\Model\Suppression\Bounce\IndexResponse;
use Mailgun\Model\Suppression\Bounce\ShowResponse;

/**
 * @see https://documentation.mailgun.com/api-suppressions.html#bounces
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class Bounce extends HttpApi
{
    use Pagination;

    /**
     * @param string $domain Domain to list bounces for
     * @param int    $limit  optional
     *
     * @return IndexResponse
     */
    public function index($domain, $limit = 100)
    {
        Assert::stringNotEmpty($domain);
        Assert::range($limit, 1, 10000, '"Limit" parameter must be between 1 and 10000');

        $params = [
            'limit' => $limit,
        ];

        $response = $this->httpGet(sprintf('/v3/%s/bounces', $domain), $params);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * @param string $domain  Domain to show bounce from
     * @param string $address Bounce address to show
     *
     * @return ShowResponse
     */
    public function show($domain, $address)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);

        $response = $this->httpGet(sprintf('/v3/%s/bounces/%s', $domain, $address));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * @param string $domain  Domain to create a bounce for
     * @param string $address Address to create a bounce for
     * @param array  $params  optional
     *
     * @return CreateResponse
     */
    public function create($domain, $address, array $params = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);

        $params['address'] = $address;

        $response = $this->httpPost(sprintf('/v3/%s/bounces', $domain), $params);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * @param string $domain  Domain to delete a bounce for
     * @param string $address Bounce address to delete
     *
     * @return DeleteResponse
     */
    public function delete($domain, $address)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);

        $response = $this->httpDelete(sprintf('/v3/%s/bounces/%s', $domain, $address));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * @param string $domain Domain to delete all bounces for
     *
     * @return DeleteResponse
     */
    public function deleteAll($domain)
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpDelete(sprintf('/v3/%s/bounces', $domain));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }
}
