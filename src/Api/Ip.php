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
use Mailgun\Model\Ip\AvailableIpsResponse;
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
     * @param bool|null $dedicated
     * @param array $requestHeaders
     * @return IndexResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
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
     * @param string $domain
     * @param array $requestHeaders
     * @return IndexResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function domainIndex(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/domains/%s/ips', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a single ip.
     * @param string $ip
     * @param array $requestHeaders
     * @return ShowResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function show(string $ip, array $requestHeaders = [])
    {
        Assert::ip($ip);

        $response = $this->httpGet(sprintf('/v3/ips/%s', $ip), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Assign a dedicated IP to the domain specified.
     * @param string $domain
     * @param string $ip
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
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
     * @param string $domain
     * @param string $ip
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function unassign(string $domain, string $ip, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::ip($ip);

        $response = $this->httpDelete(sprintf('/v3/domains/%s/ips/%s', $domain, $ip), [], $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Get all domains of an account where a specific IP is assigned
     * @param string $ip
     * @param int $limit
     * @param int $skip
     * @param string|null $search
     * @param array $requestHeaders
     * @return IndexResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function domainsByIp(string $ip, int $limit = 10, int $skip = 0, ?string $search = null, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($ip);

        $params = [
            'limit' => $limit,
            'skip' => $skip,
        ];
        if (null !== $search) {
            $params['search'] = $search;
        }

        $response = $this->httpGet(sprintf('/v3/ips/%s/domains', $ip), $params, $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Place account IP into a dedicated IP band
     * @param string $ip
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function placeAccountIpToBand(string $ip, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($ip);
        Assert::ip($ip);

        $payload = [
            'ip_band' => $ip,
        ];

        $response = $this->httpPost(sprintf('/v3/ips/%s/ip_band', $ip), $payload, $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Return the number of IPs available to the account per its billing plan
     * @param array $requestHeaders
     * @return AvailableIpsResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function numberOfIps(array $requestHeaders = [])
    {
        $response = $this->httpGet('v3/ips/request/new', [], $requestHeaders);

        return $this->hydrateResponse($response, AvailableIpsResponse::class);
    }

    /**
     * Add a new dedicated IP to the account
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function addDedicatedIp(array $requestHeaders = [])
    {
        $response = $this->httpPost('v3/ips/request/new', [], $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Remove an IP from the domain pool, unlink a DIPP or remove the domain pool
     * @param string $domain
     * @param string $ip
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function removeIpOrUnlink(string $domain, string $ip, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::ip($ip);

        $response = $this->httpDelete(sprintf('/v3/domains/%s/pool/%s', $domain, $ip), [], $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @param array $data
     * @param array $requestHeaders
     * @return mixed|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function addNewDIPPIntoAccount(array $data, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($data['description']);
        Assert::stringNotEmpty($data['name']);

        $response = $this->httpPost('/v3/ip_pools', $data, $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @param string $poolId
     * @param array $requestHeaders
     * @return mixed|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function loadDIPPInformation(string $poolId, array $requestHeaders = [])
    {
        $response = $this->httpGet(sprintf('/v3/ip_pools/%s', $poolId), [], $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @param string $poolId
     * @param string $ip
     * @param string $repPoolId
     * @param array $requestHeaders
     * @return mixed|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function deleteDIPP(string $poolId, string $ip, string $repPoolId, array $requestHeaders = [])
    {
        $response = $this->httpDelete(
            sprintf('/v3/ip_pools/%s?', $poolId,) . http_build_query(['ip' => $ip, 'pool_id' => $repPoolId]),
            [],
            $requestHeaders
        );

        return $this->hydrateResponse($response, UpdateResponse::class);
    }
}
