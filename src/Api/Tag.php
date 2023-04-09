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
     * @return IndexResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function index(string $domain, int $limit = 100)
    {
        Assert::stringNotEmpty($domain);
        Assert::range($limit, 1, 1000);

        $params = [
            'limit' => $limit,
        ];

        $response = $this->httpGet(sprintf('/v3/%s/tags', $domain), $params);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a single tag.
     * @return ShowResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function show(string $domain, string $tag)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpGet(sprintf('/v3/%s/tags/%s', $domain, $tag));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Update a tag.
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function update(string $domain, string $tag, string $description)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $params = [
            'description' => $description,
        ];

        $response = $this->httpPut(sprintf('/v3/%s/tags/%s', $domain, $tag), $params);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Returns statistics for a single tag.
     * @return StatisticsResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function stats(string $domain, string $tag, array $params)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpGet(sprintf('/v3/%s/tags/%s/stats', $domain, $tag), $params);

        return $this->hydrateResponse($response, StatisticsResponse::class);
    }

    /**
     * Removes a tag from the account.
     * @return DeleteResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function delete(string $domain, string $tag)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpDelete(sprintf('/v3/%s/tags/%s', $domain, $tag));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * @return CountryResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function countries(string $domain, string $tag)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpGet(sprintf('/v3/%s/tags/%s/stats/aggregates/countries', $domain, $tag));

        return $this->hydrateResponse($response, CountryResponse::class);
    }

    /**
     * @return ProviderResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function providers(string $domain, string $tag)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpGet(sprintf('/v3/%s/tags/%s/stats/aggregates/providers', $domain, $tag));

        return $this->hydrateResponse($response, ProviderResponse::class);
    }

    /**
     * @return DeviceResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function devices(string $domain, string $tag)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpGet(sprintf('/v3/%s/tags/%s/stats/aggregates/devices', $domain, $tag));

        return $this->hydrateResponse($response, DeviceResponse::class);
    }
}
