<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Exception;
use Mailgun\Assert;
use Mailgun\Model\Domain\DeleteResponse;
use Mailgun\Model\Domain\DomainKeyResponse;
use Mailgun\Model\Domain\IndexResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://documentation.mailgun.com/docs/mailgun/api-reference/openapi-final/tag/Domain-Keys/
 *
 */
class DomainKeys extends HttpApi
{
    private const BITS_SIZE = ['1024', '2048'];

    /**
     * Returns a list of domains on the account.
     * @param int|null $limit
     * @param string|null $page
     * @param string|null $signingDomain
     * @param string|null $selector
     * @param array $requestHeaders
     * @return IndexResponse|array
     * @throws ClientExceptionInterface
     * @throws \JsonException
     * @throws Exception
     */
    public function listKeysForDomains(?int $limit = null, ?string $page = null, ?string $signingDomain = null, ?string $selector = null, array $requestHeaders = [])
    {
        $params = [];
        if (isset($limit)) {
            Assert::range($limit, 1, 1000);
            $params['limit'] = $limit;
        }

        if (isset($page)) {
            Assert::stringNotEmpty($page);
            $params['page'] = $page;
        }

        if (isset($signingDomain)) {
            $params['signing_domain'] = $signingDomain;
        }

        if (isset($selector)) {
            $params['selector'] = $signingDomain;
        }

        $response = $this->httpGet('/v1/dkim/keys', $params, $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a list of domains on the account.
     * @param string $authorityName
     * @param array $requestHeaders
     * @return IndexResponse|array
     * @throws ClientExceptionInterface
     * @throws \JsonException
     * @throws Exception
     */
    public function listDomainKeys(string $authorityName, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($authorityName);

        $response = $this->httpGet(sprintf('/v4/domains/%s/keys', $authorityName), [], $requestHeaders);

        return $this->hydrateResponse($response, DomainKeyResponse::class);
    }

    /**
     * @param string $signingDomain
     * @param string $selector
     * @param string|null $bits
     * @param array $requestHeaders
     * @return mixed|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     * @throws Exception
     */
    public function createDomainKey(string $signingDomain, string $selector, ?string $bits = null, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($signingDomain);
        Assert::stringNotEmpty($selector);

        $params = [
            'signing_domain' => $signingDomain,
            'selector' => $selector,
        ];

        if (!empty($bits)) {
            Assert::oneOf(
                $bits,
                self::BITS_SIZE,
                'Length of your domain’s generated DKIM key must be 1024 or 2048'
            );
            $params['bits'] = $bits;
        }

        $response = $this->httpPost('/v1/dkim/keys', $params, $requestHeaders);

        return $this->hydrateResponse($response, DomainKeyResponse::class);
    }

    /**
     * @param string $signingDomain
     * @param string $selector
     * @param array $requestHeaders
     * @return mixed|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function deleteDomainKey(string $signingDomain, string $selector, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($signingDomain);
        Assert::stringNotEmpty($selector);

        $params = [
            'signing_domain' => $signingDomain,
            'selector' => $selector,
        ];

        $response = $this->httpDelete('/v1/dkim/keys', $params, $requestHeaders);

        return $this->hydrateResponse($response, DeleteResponse::class);
    }
}
