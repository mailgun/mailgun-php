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
use Mailgun\Exception\HttpClientException;
use Mailgun\Model\SubAccounts\CreateResponse;
use Mailgun\Model\SubAccounts\ShowResponse;
use Psr\Http\Client\ClientExceptionInterface;

class SubAccounts extends HttpApi
{
    use Pagination;

    private const ENTITY_API_URL = '/v5/accounts/subaccounts';

    /**
     * @param string $name
     * @return CreateResponse|null
     * @throws ClientExceptionInterface
     */
    public function create(string $name): ?CreateResponse
    {
        Assert::stringNotEmpty($name);

        $params['name'] = $name;
        $response = $this->httpPost(self::ENTITY_API_URL, $params);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * @param array $params
     * @return ShowResponse|null
     * @throws ClientExceptionInterface|HttpClientException
     */
    public function show(array $params = []): ?ShowResponse
    {
        $response = $this->httpGet(self::ENTITY_API_URL, $params);

        return $this->hydrateResponse($response, ShowResponse::class);
    }
}
