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
use Mailgun\Model\Tag\DeleteResponse;
use Mailgun\Model\Tag\IndexResponse;
use Mailgun\Model\Tag\ShowResponse;
use Mailgun\Model\Tag\StatisticsResponse;
use Mailgun\Model\Tag\UpdateResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * {@link https://documentation.mailgun.com/api-tags.html#tags}.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Tag extends HttpApi
{
    /**
     * Returns a list of tags.

     *
     * @return IndexResponse|ResponseInterface
     */
    public function index(string $domain, int $limit = 100)
    {
        Assert::stringNotEmpty($domain);
        $params = [
            'limit' => $limit,
        ];

        $response = $this->httpGet(sprintf('/v3/%s/tags', $domain), $params);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a single tag.
     *
     * @return ShowResponse|ResponseInterface
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
     *
     *
     * @return UpdateResponse|ResponseInterface
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
     *
     *
     * @return StatisticsResponse|ResponseInterface
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
     *
     *
     * @return DeleteResponse|ResponseInterface
     */
    public function delete(string $domain, string $tag)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpDelete(sprintf('/v3/%s/tags/%s', $domain, $tag));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }
}
