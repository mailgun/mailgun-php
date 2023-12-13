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
use Mailgun\Model\Ip\IndexResponse;
use Mailgun\Model\Ip\ShowResponse;
use Mailgun\Model\Ip\UpdateResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-ips.html
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Ip extends HttpApi
{
    /**
     * Returns a list of IPs.
     * @param  bool|null                       $dedicated
     * @param  array                           $requestHeaders
     * @return IndexResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function index(?bool $dedicated = null, array $requestHeaders = [])
    {
        $params = [];
        if (null !== $dedicated) {
            Assert::boolean($dedicated);
            $params['dedicated'] = $dedicated;
        }

        $response = $this->httpGet('/v3/ips', $params, $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a list of IPs assigned to a domain.
     * @param  string                          $domain
     * @param  array                           $requestHeaders
     * @return IndexResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function domainIndex(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/domains/%s/ips', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a single ip.
     * @param  string                         $ip
     * @param  array                          $requestHeaders
     * @return ShowResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function show(string $ip, array $requestHeaders = [])
    {
        Assert::ip($ip);

        $response = $this->httpGet(sprintf('/v3/ips/%s', $ip), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Assign a dedicated IP to the domain specified.
     * @param  string                           $domain
     * @param  string                           $ip
     * @param  array                            $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function assign(string $domain, string $ip, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::ip($ip);

        $params = [
            'ip' => $ip,
        ];

        $response = $this->httpPost(sprintf('/v3/domains/%s/ips', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Unassign an IP from the domain specified.
     * @param  string                           $domain
     * @param  string                           $ip
     * @param  array                            $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function unassign(string $domain, string $ip, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::ip($ip);

        $response = $this->httpDelete(sprintf('/v3/domains/%s/ips/%s', $domain, $ip), [], $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }
}
