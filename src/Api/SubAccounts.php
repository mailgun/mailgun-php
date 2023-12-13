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
use Mailgun\Model\SubAccounts\CreateResponse;
use Mailgun\Model\SubAccounts\IndexResponse;
use Mailgun\Model\SubAccounts\ShowResponse;
use Psr\Http\Client\ClientExceptionInterface;

class SubAccounts extends HttpApi
{
    use Pagination;

    private const ENTITY_API_URL = '/v5/accounts/subaccounts';

    /**
     * @param  string                   $name
     * @return CreateResponse|null
     * @throws ClientExceptionInterface
     */
    public function create(string $name): ?CreateResponse
    {
        Assert::stringNotEmpty($name);

        $response = $this->httpPost(self::ENTITY_API_URL, ['name' => $name]);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * @param  array                    $params
     * @return IndexResponse|null
     * @throws ClientExceptionInterface
     */
    public function index(array $params = []): ?IndexResponse
    {
        if (isset($params['limit'])) {
            Assert::range($params['limit'], 1, 10);
        }
        if (isset($params['sort'])) {
            Assert::isArray($params['sort']);
        }
        if (isset($params['enabled'])) {
            Assert::boolean($params['enabled']);
        }

        $response = $this->httpGet(self::ENTITY_API_URL, $params);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * @param  string                   $id
     * @return ShowResponse|null
     * @throws ClientExceptionInterface
     */
    public function show(string $id): ?ShowResponse
    {
        Assert::notEmpty($id);

        $response = $this->httpGet(sprintf(self::ENTITY_API_URL.'/%s', $id));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * @param  string                   $id
     * @return ShowResponse|null
     * @throws ClientExceptionInterface
     */
    public function disable(string $id): ?ShowResponse
    {
        Assert::notEmpty($id);

        $response = $this->httpPost(sprintf(self::ENTITY_API_URL.'/%s/disable', $id));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * @param  string                   $id
     * @return ShowResponse|null
     * @throws ClientExceptionInterface
     */
    public function enable(string $id): ?ShowResponse
    {
        Assert::notEmpty($id);

        $response = $this->httpPost(sprintf(self::ENTITY_API_URL.'/%s/enable', $id), ['id' => $id]);

        return $this->hydrateResponse($response, ShowResponse::class);
    }
}
