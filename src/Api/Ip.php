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
use Mailgun\Model\Ip\IpDetailsResponse;
use Mailgun\Model\Ip\IpPoolDomainsResponse;
use Mailgun\Model\Ip\IpPoolResponse;
use Mailgun\Model\Ip\IpPoolsResponse;
use Mailgun\Model\Ip\IpReferenceResponse;
use Mailgun\Model\Ip\ShowResponse;
use Mailgun\Model\Ip\UpdateResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ips
 * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Ip extends HttpApi
{
    // -------------------------------------------------------------------------
    // IPs
    // -------------------------------------------------------------------------

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ips/get-v3-ips
     *
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
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ips/get-v3-ips--ip-
     *
     * Returns a single IP.
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
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ips/get-v3-ips--ip--domains
     *
     * Get all domains of an account where a specific IP is assigned.
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
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ips/post-v3-ips--ip--domains
     *
     * Assign an IP to all domains within the account.
     * @param string $ip
     * @param array $requestHeaders
     * @return IpReferenceResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function assignIpToAllDomains(string $ip, array $requestHeaders = [])
    {
        Assert::ip($ip);

        $response = $this->httpPost(sprintf('/v3/ips/%s/domains', $ip), [], $requestHeaders);

        return $this->hydrateResponse($response, IpReferenceResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ips/delete-v3-ips--ip--domains
     *
     * Remove an IP from all account domains. The alternative IP will replace it on all affected domains.
     * @param string $ip
     * @param string $alternative IP that will replace the removed IP on all domains
     * @param array $requestHeaders
     * @return IpReferenceResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function removeIpFromAllDomains(string $ip, string $alternative, array $requestHeaders = [])
    {
        Assert::ip($ip);
        Assert::ip($alternative);

        $path = sprintf('/v3/ips/%s/domains?%s', $ip, http_build_query(['alternative' => $alternative]));
        $response = $this->httpDelete($path, [], $requestHeaders);

        return $this->hydrateResponse($response, IpReferenceResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ips/post-v3-ips--addr--ip-band
     *
     * Place account IP into a dedicated IP band.
     * @param string $ip
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function placeAccountIpToBand(string $ip, array $requestHeaders = [])
    {
        Assert::ip($ip);

        $response = $this->httpPost(sprintf('/v3/ips/%s/ip_band', $ip), ['ip_band' => $ip], $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ips/get-v3-ips-request-new
     *
     * Return the number of IPs available to the account per its billing plan.
     * @param array $requestHeaders
     * @return AvailableIpsResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function numberOfIps(array $requestHeaders = [])
    {
        $response = $this->httpGet('/v3/ips/request/new', [], $requestHeaders);

        return $this->hydrateResponse($response, AvailableIpsResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ips/post-v3-ips-request-new
     *
     * Add a new dedicated IP to the account.
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function addDedicatedIp(array $requestHeaders = [])
    {
        $response = $this->httpPost('/v3/ips/request/new', [], $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ips/get-v3-ips-details-all
     *
     * List IPs belonging to the account and subaccounts with full details.
     * @param array $params Optional filters: limit, skip, pool_id, domain_id, subaccount_id, ip, sort_by, sort_order
     * @param array $requestHeaders
     * @return IpDetailsResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function listIpsDetailed(array $params = [], array $requestHeaders = [])
    {
        $response = $this->httpGet('/v3/ips/details/all', $params, $requestHeaders);

        return $this->hydrateResponse($response, IpDetailsResponse::class);
    }

    // -------------------------------------------------------------------------
    // Domain IPs
    // -------------------------------------------------------------------------

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

        $response = $this->httpPost(sprintf('/v3/domains/%s/ips', $domain), ['ip' => $ip], $requestHeaders);

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
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/delete-v3-domains--name--pool--ip-
     *
     * Remove an IP from the domain pool, unlink a DIPP, or remove the domain pool.
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

    // -------------------------------------------------------------------------
    // IP Pools (DIPPs)
    // -------------------------------------------------------------------------

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/get-v3-ip-pools
     *
     * List all dedicated IP pools of the account.
     * @param array $requestHeaders
     * @return IpPoolsResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function listIpPools(array $requestHeaders = [])
    {
        $response = $this->httpGet('/v3/ip_pools', [], $requestHeaders);

        return $this->hydrateResponse($response, IpPoolsResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/post-v3-ip-pools
     *
     * Create a new dedicated IP pool (DIPP).
     * @param string $name
     * @param string $description
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function createIpPool(string $name, string $description, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($name);
        Assert::stringNotEmpty($description);

        $response = $this->httpPost('/v3/ip_pools', ['name' => $name, 'description' => $description], $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/post-v3-ip-pools
     *
     * @deprecated Use createIpPool() instead.
     * @param array $data Must contain 'name' and 'description' keys
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function addNewDIPPIntoAccount(array $data, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($data['name'] ?? '');
        Assert::stringNotEmpty($data['description'] ?? '');

        $response = $this->httpPost('/v3/ip_pools', $data, $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/get-v3-ip-pools--pool-id-
     *
     * Retrieve details for a specific IP pool.
     * @param string $poolId
     * @param array $requestHeaders
     * @return IpPoolResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function loadDIPPInformation(string $poolId, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($poolId);

        $response = $this->httpGet(sprintf('/v3/ip_pools/%s', $poolId), [], $requestHeaders);

        return $this->hydrateResponse($response, IpPoolResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/patch-v3-ip-pools--pool-id-
     *
     * Modify a DIPP's properties and IP membership.
     * Supported keys: add_ip, remove_ip, name, description, link_domain, unlink_domain.
     * @param string $poolId
     * @param array $data
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function updateIpPool(string $poolId, array $data, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($poolId);

        $response = $this->httpPatch(sprintf('/v3/ip_pools/%s', $poolId), $data, $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/delete-v3-ip-pools--pool-id-
     *
     * Delete a dedicated IP pool. Optionally provide a replacement pool or IP.
     * @param string $poolId
     * @param string|null $replacementIp Optional IP to replace the pool on linked domains
     * @param string|null $replacementPoolId Optional pool ID to replace this pool on linked domains
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function deleteDIPP(string $poolId, ?string $replacementIp = null, ?string $replacementPoolId = null, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($poolId);

        $params = [];
        if ($replacementIp !== null) {
            $params['ip'] = $replacementIp;
        }
        if ($replacementPoolId !== null) {
            $params['pool_id'] = $replacementPoolId;
        }

        $path = sprintf('/v3/ip_pools/%s', $poolId);
        if (!empty($params)) {
            $path .= '?' . http_build_query($params);
        }

        $response = $this->httpDelete($path, [], $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/get-v3-ip-pools--pool-id--domains
     *
     * List domains linked to a specific IP pool.
     * @param string $poolId
     * @param int $limit
     * @param string|null $page Encoded page identifier from a previous response
     * @param array $requestHeaders
     * @return IpPoolDomainsResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function getIpPoolDomains(string $poolId, int $limit = 10, ?string $page = null, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($poolId);

        $params = ['limit' => $limit];
        if ($page !== null) {
            $params['page'] = $page;
        }

        $response = $this->httpGet(sprintf('/v3/ip_pools/%s/domains', $poolId), $params, $requestHeaders);

        return $this->hydrateResponse($response, IpPoolDomainsResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/put-v3-ip-pools--pool-id--ips--ip-
     *
     * Add a single IP to a dedicated IP pool.
     * @param string $poolId
     * @param string $ip
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function addIpToPool(string $poolId, string $ip, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($poolId);
        Assert::ip($ip);

        $response = $this->httpPut(sprintf('/v3/ip_pools/%s/ips/%s', $poolId, $ip), [], $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/delete-v3-ip-pools--pool-id--ips--ip-
     *
     * Remove an IP from a dedicated IP pool.
     * @param string $poolId
     * @param string $ip
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function removeIpFromPool(string $poolId, string $ip, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($poolId);
        Assert::ip($ip);

        $response = $this->httpDelete(sprintf('/v3/ip_pools/%s/ips/%s', $poolId, $ip), [], $requestHeaders);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/post-v3-ip-pools--pool-id--ips.json
     *
     * Add multiple IPs to a dedicated IP pool.
     * @param string $poolId
     * @param string[] $ips
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function addIpsToPool(string $poolId, array $ips, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($poolId);
        Assert::isArray($ips);

        $requestHeaders['Content-Type'] = 'application/json';
        $response = $this->httpPost(
            sprintf('/v3/ip_pools/%s/ips.json', $poolId),
            ['ips' => $ips],
            $requestHeaders
        );

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/put-v3-ip-pools--pool-id--delegate
     *
     * Delegate a dedicated IP pool to a subaccount.
     * @param string $poolId
     * @param string $subaccountId
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function delegateIpPool(string $poolId, string $subaccountId, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($poolId);
        Assert::stringNotEmpty($subaccountId);

        $response = $this->httpPut(
            sprintf('/v3/ip_pools/%s/delegate', $poolId),
            ['subaccount_id' => $subaccountId],
            $requestHeaders
        );

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/ip-pools/delete-v3-ip-pools--pool-id--delegate
     *
     * Revoke a dedicated IP pool delegation from a subaccount.
     * @param string $poolId
     * @param string $subaccountId
     * @param array $requestHeaders
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function revokeDelegatedIpPool(string $poolId, string $subaccountId, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($poolId);
        Assert::stringNotEmpty($subaccountId);

        $response = $this->httpDelete(
            sprintf('/v3/ip_pools/%s/delegate', $poolId),
            ['subaccount_id' => $subaccountId],
            $requestHeaders
        );

        return $this->hydrateResponse($response, UpdateResponse::class);
    }
}
