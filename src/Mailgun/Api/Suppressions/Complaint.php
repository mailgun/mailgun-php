<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Model\Suppressions\Complaint\CreateResponse;
use Mailgun\Model\Suppressions\Complaint\DeleteResponse;
use Mailgun\Model\Suppressions\Complaint\IndexResponse;
use Mailgun\Model\Suppressions\Complaint\ShowResponse;

/**
 * @see https://documentation.mailgun.com/api-suppressions.html#complaints
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class Complaint extends HttpApi
{
    use Pagination;

    /**
     * @param string $domain Domain to get complaints for
     * @param int    $limit  optional
     *
     * @return IndexResponse
     */
    public function index($domain, $limit = 100)
    {
        Assert::stringNotEmpty($domain);
        Assert::range($limit, 1, 10000, 'Limit parameter must be between 1 and 10000');

        $params = [
            'limit' => $limit,
        ];

        $response = $this->httpGet(sprintf('/v3/%s/complaints', $domain), $params);

        return $this->safeDeserialize($response, IndexResponse::class);
    }

    /**
     * @param string $domain  Domain to show complaint for
     * @param string $address Complaint address
     *
     * @return ShowResponse
     */
    public function show($domain, $address)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);
        $response = $this->httpGet(sprintf('/v3/%s/complaints/%s', $domain, $address));

        return $this->safeDeserialize($response, ShowResponse::class);
    }

    /**
     * @param string $domain  Domain to create complaint for
     * @param string $address Complaint address
     * @param array  $params  optional
     *
     * @return CreateResponse
     */
    public function create($domain, $address, $code = null, $error = null, $createdAt = null)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);

        $params['address'] = $address;

        $response = $this->httpPost(sprintf('/v3/%s/complaints', $domain), $params);

        return $this->safeDeserialize($response, CreateResponse::class);
    }

    /**
     * @param string $domain  Domain to delete complaint for
     * @param string $address Complaint address
     *
     * @return DeleteResponse
     */
    public function delete($domain, $address)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);

        $response = $this->httpDelete(sprintf('/v3/%s/complaints/%s', $domain, $address));

        return $this->safeDeserialize($response, DeleteResponse::class);
    }

    /**
     * @param string $domain Domain to delete all bounces for
     *
     * @return DeleteResponse
     */
    public function deleteAll($domain)
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpDelete(sprintf('/v3/%s/complaints', $domain));

        return $this->safeDeserialize($response, DeleteResponse::class);
    }
}
