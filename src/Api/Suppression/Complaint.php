<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api\Suppression;

use Mailgun\Api\HttpApi;
use Mailgun\Api\Pagination;
use Mailgun\Assert;
use Mailgun\Model\Suppression\Complaint\CreateResponse;
use Mailgun\Model\Suppression\Complaint\DeleteResponse;
use Mailgun\Model\Suppression\Complaint\IndexResponse;
use Mailgun\Model\Suppression\Complaint\ShowResponse;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-suppressions.html#complaints
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class Complaint extends HttpApi
{
    use Pagination;

    /**
     * @param  string                   $domain         Domain to get complaints for
     * @param  int                      $limit          optional
     * @param  array                    $requestHeaders
     * @return IndexResponse
     * @throws ClientExceptionInterface
     */
    public function index(string $domain, int $limit = 100, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::range($limit, 1, 10000, 'Limit parameter must be between 1 and 10000');

        $params = [
            'limit' => $limit,
        ];

        $response = $this->httpGet(sprintf('/v3/%s/complaints', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * @param string $domain  Domain to show complaint for
     * @param string $address Complaint address
     *
     * @return ShowResponse
     */
    public function show(string $domain, string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);
        $response = $this->httpGet(sprintf('/v3/%s/complaints/%s', $domain, $address), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * @param  string                   $domain         Domain to create complaint for
     * @param  string                   $address        Complaint address
     * @param  string|null              $createdAt      (optional) rfc2822 compliant format. (new \DateTime())->format('r')
     * @param  array                    $requestHeaders
     * @return CreateResponse
     * @throws ClientExceptionInterface
     */
    public function create(string $domain, string $address, ?string $createdAt = null, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);

        $params['address'] = $address;
        if (null !== $createdAt) {
            Assert::stringNotEmpty($createdAt);
            $params['created_at'] = $createdAt;
        }

        $response = $this->httpPost(sprintf('/v3/%s/complaints', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * @param  string                   $domain         Domain to delete complaint for
     * @param  string                   $address        Complaint address
     * @param  array                    $requestHeaders
     * @return DeleteResponse
     * @throws ClientExceptionInterface
     */
    public function delete(string $domain, string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);

        $response = $this->httpDelete(sprintf('/v3/%s/complaints/%s', $domain, $address), [], $requestHeaders);

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * @param  string                   $domain         Domain to delete all bounces for
     * @param  array                    $requestHeaders
     * @return DeleteResponse
     * @throws ClientExceptionInterface
     */
    public function deleteAll(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpDelete(sprintf('/v3/%s/complaints', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, DeleteResponse::class);
    }
}
