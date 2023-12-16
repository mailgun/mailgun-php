<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api\Suppression;

use InvalidArgumentException;
use Mailgun\Api\HttpApi;
use Mailgun\Api\Pagination;
use Mailgun\Assert;
use Mailgun\Model\Suppression\Whitelist\CreateResponse;
use Mailgun\Model\Suppression\Whitelist\DeleteAllResponse;
use Mailgun\Model\Suppression\Whitelist\DeleteResponse;
use Mailgun\Model\Suppression\Whitelist\ImportResponse;
use Mailgun\Model\Suppression\Whitelist\IndexResponse;
use Mailgun\Model\Suppression\Whitelist\ShowResponse;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-suppressions.html#whitelists
 *
 * @author Artem Bondarenko <artem@uartema.com>
 */
class Whitelist extends HttpApi
{
    use Pagination;

    /**
     * @param  string                   $domain         Domain to get whitelist for
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

        $response = $this->httpGet(sprintf('/v3/%s/whitelists', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * @param  string                   $domain         Domain to show whitelist for
     * @param  string                   $address        whitelist address
     * @param  array                    $requestHeaders
     * @return ShowResponse
     * @throws ClientExceptionInterface
     */
    public function show(string $domain, string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);

        $response = $this->httpGet(sprintf('/v3/%s/whitelists/%s', $domain, $address), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * @param  string                   $domain         Domain to create whitelist for
     * @param  string                   $address        whitelist email address or domain name
     * @param  array                    $requestHeaders
     * @return CreateResponse
     * @throws ClientExceptionInterface
     */
    public function create(string $domain, string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);

        $params = [];

        if (false !== filter_var($address, FILTER_VALIDATE_EMAIL)) {
            $params['address'] = $address;
        } elseif (false !== filter_var($address, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            $params['domain'] = $address;
        } else {
            throw new InvalidArgumentException('Address should be valid email or domain name');
        }

        $response = $this->httpPost(sprintf('/v3/%s/whitelists', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * @param string $domain   Domain to create whitelist for
     * @param string $filePath csv file path
     *
     * @return ImportResponse
     */
    public function import(string $domain, string $filePath)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($filePath);
        Assert::fileExists($filePath);

        $response = $this->httpPost(
            sprintf('/v3/%s/whitelists/import', $domain),
            ['file' => fopen($filePath, 'r')],
            [
                'filename' => basename($filePath),
            ]
        );

        return $this->hydrateResponse($response, ImportResponse::class);
    }

    /**
     * @param  string                   $domain         Domain to delete whitelist for
     * @param  string                   $address        whitelist address
     * @param  array                    $requestHeaders
     * @return DeleteResponse
     * @throws ClientExceptionInterface
     */
    public function delete(string $domain, string $address, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($address);

        $response = $this->httpDelete(sprintf('/v3/%s/whitelists/%s', $domain, $address), [], $requestHeaders);

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * @param  string                   $domain         Domain to delete all whitelists for
     * @param  array                    $requestHeaders
     * @return DeleteAllResponse
     * @throws ClientExceptionInterface
     */
    public function deleteAll(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpDelete(sprintf('/v3/%s/whitelists', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, DeleteAllResponse::class);
    }
}
