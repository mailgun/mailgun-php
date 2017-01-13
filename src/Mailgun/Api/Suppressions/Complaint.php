<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Api\Pagination;
use Mailgun\Resource\Api\Suppressions\Complaint\CreateResponse;
use Mailgun\Resource\Api\Suppressions\Complaint\DeleteResponse;
use Mailgun\Resource\Api\Suppressions\Complaint\IndexResponse;
use Mailgun\Resource\Api\Suppressions\Complaint\ShowResponse;
use Mailgun\Resource\Api\Suppressions\Complaint\UpdateResponse;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class Complaint extends HttpApi
{
    use Pagination;

    /**
     * @param string $domain
     *
     * @return IndexResponse
     */
    public function index($domain)
    {
        Assert::notEmpty($domain);
        Assert::range($limit, 1, 10000, 'Limit parameter must be between 1 and 10000');

        $response = $this->httpGet(sprintf('/v3/%s/complaints', $domain));

        return $this->safeDeserialize($response, IndexResponse::class);
    }

    /**
     * @param string $domain
     * @param string $address
     *
     * @return ShowResponse
     */
    public function show($domain, $address)
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($address);
        $response = $this->httpGet(sprintf('/v3/%s/complaints/%s', $domain, $address));

        return $this->safeDeserialize($response, ShowResponse::class);
    }

    /**
     * @param string    $domain
     * @param string    $address
     * @param string    $code      optional
     * @param string    $error     optional
     * @param \DateTime $createdAt optional
     *
     * @return CreateResponse
     */
    public function create($domain, $address, $code = null, $error = null, $createdAt = null)
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($address);

        $params = [
            'address' => $address,
        ];

        foreach ([ 'code' => $code, 'error' => $error, 'created_at' => $createdAt ] as $k => $v) {
            if ( !empty($v) ) {
                $params[$k] = $v;
            }
        }

        $response = $this->httpPost(sprintf('/v3/%s/complaints', $domain), $params);

        return $this->safeDeserialize($response, CreateResponse::class);
    }

    /**
     * @param string $domain
     * @param string $address
     *
     * @return DeleteResponse
     */
    public function delete($domain, $address)
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($address);
        $response = $this->httpDelete(sprintf('/v3/%s/complaints/%s', $domain, $address));

        return $this->safeDeserialize($response, DeleteResponse::class);
    }

    /**
     * @param string $domain
     *
     * @return DeleteResponse
     */
    public function deleteAll($domain)
    {
        Assert::notEmpty($domain);
        $response = $this->httpDelete(sprintf('/v3/%s/complaints', $domain));

        return $this->safeDeserialize($response, DeleteResponse::class);
    }

    /*
     * INDEX PAGINATION
     */

    /**
     * @param IndexResponse $index
     *
     * @return IndexResponse|null
     */
    public function getPaginationNext(IndexResponse $index)
    {
        return $this->getPaginationUrl($index->getNextUrl(), IndexResponse::class);
    }

    /**
     * @param IndexResponse $index
     *
     * @return IndexResponse|null
     */
    public function getPaginationPrevious(IndexResponse $index)
    {
        return $this->getPaginationUrl($index->getPreviousUrl(), IndexResponse::class);
    }

    /**
     * @param IndexResponse $index
     *
     * @return IndexResponse|null
     */
    public function getPaginationFirst(IndexResponse $index)
    {
        return $this->getPaginationUrl($index->getFirstUrl(), IndexResponse::class);
    }

    /**
     * @param IndexResponse $index
     *
     * @return IndexResponse|null
     */
    public function getPaginationLast(IndexResponse $index)
    {
        return $this->getPaginationUrl($index->getLastUrl(), IndexResponse::class);
    }
}
