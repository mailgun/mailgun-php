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
use Mailgun\Model\Tag\CountryResponse;
use Mailgun\Model\Tag\DeleteResponse;
use Mailgun\Model\Tag\DeviceResponse;
use Mailgun\Model\Tag\IndexResponse;
use Mailgun\Model\Tag\ProviderResponse;
use Mailgun\Model\Tag\ShowResponse;
use Mailgun\Model\Tag\StatisticsResponse;
use Mailgun\Model\Tag\TagLimitResponse;
use Mailgun\Model\Tag\UpdateResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-tags.html
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Tag extends HttpApi
{
    use Pagination;

    /**
     * Returns a list of tags.
     * @param  string                          $domain
     * @param  int                             $limit
     * @param  array                           $requestHeaders
     * @return IndexResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function index(string $domain, int $limit = 100, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::range($limit, 1, 1000);

        $params = [
            'limit' => $limit,
        ];

        $response = $this->httpGet(sprintf('/v3/%s/tags', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a single tag.
     * @param  string                         $domain
     * @param  string                         $tag
     * @param  array                          $requestHeaders
     * @return ShowResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function show(string $domain, string $tag, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpGet(sprintf('/v3/%s/tags/%s', $domain, $tag), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Update a tag.
     * @param  string                           $domain
     * @param  string                           $tag
     * @param  string                           $description
     * @param  array                            $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function update(string $domain, string $tag, string $description, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $params = [
            'description' => $description,
        ];

        $response = $this->httpPut(sprintf('/v3/%s/tags/%s', $domain, $tag), $params, $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Returns statistics for a single tag.
     * @param string $domain
     * @param array $params
     * @param array $requestHeaders
     * @return StatisticsResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function stats(string $domain, array $params, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($params['event']);
        Assert::stringNotEmpty($params['tag']);

        $response = $this->httpGet(sprintf('/v3/%s/tag/stats', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, StatisticsResponse::class);
    }

    /**
     * Removes a tag from the account.
     * @param  string                           $domain
     * @param  string                           $tag
     * @param  array                            $requestHeaders
     * @return DeleteResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function delete(string $domain, string $tag, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpDelete(sprintf('/v3/%s/tag', $domain), ['tag' => $tag], $requestHeaders);

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * @param  string                            $domain
     * @param  string                            $tag
     * @param  array                             $requestHeaders
     * @return CountryResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function countries(string $domain, string $tag, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpGet(sprintf('/v3/%s/tags/%s/stats/aggregates/countries', $domain, $tag), [], $requestHeaders);

        return $this->hydrateResponse($response, CountryResponse::class);
    }

    /**
     * @param  string                             $domain
     * @param  string                             $tag
     * @param  array                              $requestHeaders
     * @return ProviderResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function providers(string $domain, string $tag, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpGet(sprintf('/v3/%s/tags/%s/stats/aggregates/providers', $domain, $tag), [], $requestHeaders);

        return $this->hydrateResponse($response, ProviderResponse::class);
    }

    /**
     * @param  string                           $domain
     * @param  string                           $tag
     * @param  array                            $requestHeaders
     * @return DeviceResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function devices(string $domain, string $tag, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpGet(sprintf('/v3/%s/tags/%s/stats/aggregates/devices', $domain, $tag), [], $requestHeaders);

        return $this->hydrateResponse($response, DeviceResponse::class);
    }

    /**
     * @param string $domain
     * @param array $requestHeaders
     * @return TagLimitResponse
     * @throws ClientExceptionInterface
     */
    public function getTagLimits(string $domain, array $requestHeaders = []): TagLimitResponse
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/domains/%s/limits/tag', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, TagLimitResponse::class);
    }
}
