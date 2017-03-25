<?php

/*
 * Copyright (C) 2013-2016 Mailgun
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
     * @param string $domain
     * @param int    $limit
     *
     * @return IndexResponse|ResponseInterface
     */
    public function index($domain, $limit = 100)
    {
        Assert::stringNotEmpty($domain);
        Assert::integer($limit);

        $params = [
            'limit' => $limit,
        ];

        $response = $this->httpGet(sprintf('/v3/%s/tags', $domain), $params);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a single tag.
     *
     * @param string $domain Name of the domain
     * @param string $tag
     *
     * @return ShowResponse|ResponseInterface
     */
    public function show($domain, $tag)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpGet(sprintf('/v3/%s/tags/%s', $domain, $tag));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Update a tag.
     *
     * @param string $domain
     * @param string $tag
     * @param string $description
     *
     * @return UpdateResponse|ResponseInterface
     */
    public function update($domain, $tag, $description)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);
        Assert::string($description);

        $params = [
            'description' => $description,
        ];

        $response = $this->httpPut(sprintf('/v3/%s/tags/%s', $domain, $tag), $params);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Returns statistics for a single tag.
     *
     * @param string $domain Name of the domain
     * @param string $tag
     * @param array  $params
     *
     * @return StatisticsResponse|ResponseInterface
     */
    public function stats($domain, $tag, array $params)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);
        Assert::isArray($params);

        $response = $this->httpGet(sprintf('/v3/%s/tags/%s/stats', $domain, $tag), $params);

        return $this->hydrateResponse($response, StatisticsResponse::class);
    }

    /**
     * Removes a tag from the account.
     *
     * @param string $domain Name of the domain
     * @param string $tag
     *
     * @return DeleteResponse|ResponseInterface
     */
    public function delete($domain, $tag)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($tag);

        $response = $this->httpDelete(sprintf('/v3/%s/tags/%s', $domain, $tag));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }
}
