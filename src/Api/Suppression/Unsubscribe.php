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
use Mailgun\Model\Suppression\Unsubscribe\CreateResponse;
use Mailgun\Model\Suppression\Unsubscribe\DeleteResponse;
use Mailgun\Model\Suppression\Unsubscribe\IndexResponse;
use Mailgun\Model\Suppression\Unsubscribe\ShowResponse;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-suppressions.html#unsubscribes
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class Unsubscribe extends HttpApi
{
    use Pagination;

    /**
     * @param  string                   $domain         Domain to get unsubscribes for
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

        $response = $this->httpGet(sprintf('/v3/%s/unsubscribes', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * @param  string                   $domain         Domain to show unsubscribe for
     * @param  string                   $address        Unsubscribe address
     * @param  array                    $requestHeaders
     * @return ShowResponse
     * @throws ClientExceptionInterface
     */
    public function show(string $domain, string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);

        $response = $this->httpGet(sprintf('/v3/%s/unsubscribes/%s', $domain, $address), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * @param  string                   $domain         Domain to create unsubscribe for
     * @param  string                   $address        Unsubscribe address
     * @param  array                    $params         optional
     * @param  array                    $requestHeaders
     * @return CreateResponse
     * @throws ClientExceptionInterface
     */
    public function create(string $domain, string $address, array $params = [], array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);

        $params['address'] = $address;

        $response = $this->httpPost(sprintf('/v3/%s/unsubscribes', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * @param  string                   $domain         Domain to delete unsubscribe for
     * @param  string                   $address        Unsubscribe address
     * @param  string|null              $tag            Unsubscribe tag
     * @param  array                    $requestHeaders
     * @return DeleteResponse
     * @throws ClientExceptionInterface
     */
    public function delete(string $domain, string $address, string $tag = null, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);
        Assert::nullOrStringNotEmpty($tag);

        $params = [];
        if (!is_null($tag)) {
            $params['tag'] = $tag;
        }

        $response = $this->httpDelete(sprintf('/v3/%s/unsubscribes/%s', $domain, $address), $params, $requestHeaders);

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * @param  string                   $domain         Domain to delete all unsubscribes for
     * @param  array                    $requestHeaders
     * @return DeleteResponse
     * @throws ClientExceptionInterface
     */
    public function deleteAll(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpDelete(sprintf('/v3/%s/unsubscribes', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * @param string $domain
     * @param string $filePath
     * @return mixed
     * @throws ClientExceptionInterface
     */
    public function import(string $domain, string $filePath)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($filePath);
        Assert::fileExists($filePath);

        $response = $this->httpPost(
            sprintf('/v3/%s/unsubscribes/import', $domain),
            ['file' => fopen($filePath, 'r')],
            [
                'filename' => basename($filePath),
            ]
        );

        return $this->hydrateResponse($response, ShowResponse::class);
    }
}
