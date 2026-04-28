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
use Mailgun\Model\DynamicIpPools\AssignableDomainsResponse;
use Mailgun\Model\DynamicIpPools\DomainsResponse;
use Mailgun\Model\DynamicIpPools\HistoryResponse;
use Mailgun\Model\DynamicIpPools\IndexResponse;
use Mailgun\Model\DynamicIpPools\MessageResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools
 */
class DynamicIpPools extends HttpApi
{
    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/post-v3-domains-name-dynamic-pools
     *
     * Enroll a domain into dynamic IP pools.
     *
     * @param string $domain
     * @param string $replacementIp Valid IP address or 'shared'
     * @param array  $requestHeaders
     * @return MessageResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function enrollDomain(string $domain, string $replacementIp, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($replacementIp);

        $params = ['replacement_ip' => $replacementIp];

        $response = $this->httpPost(sprintf('/v3/domains/%s/dynamic_pools', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, MessageResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/delete-v3-domains-name-dynamic-pools
     *
     * Remove a domain from dynamic IP pools.
     *
     * @param string      $domain
     * @param string|null $replacementIp     Valid IP address(es) or 'shared'. Cannot be used with $replacementPoolId.
     * @param string|null $replacementPoolId Valid dedicated IP pool ID. Cannot be used with $replacementIp.
     * @param array       $requestHeaders
     * @return MessageResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function removeDomain(string $domain, ?string $replacementIp = null, ?string $replacementPoolId = null, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $params = [];
        if (null !== $replacementIp) {
            $params['replacement_ip'] = $replacementIp;
        }
        if (null !== $replacementPoolId) {
            $params['replacement_pool_id'] = $replacementPoolId;
        }

        $response = $this->httpDelete(sprintf('/v3/domains/%s/dynamic_pools', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, MessageResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/get-v3-domains-dynamic-pools-assignable
     *
     * List domains that are assignable to dynamic IP pools.
     *
     * @param string|null $subaccountId Filter domains for a specific subaccount
     * @param string|null $domain       Regex search term for domain filtering
     * @param array       $requestHeaders
     * @return AssignableDomainsResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function listAssignableDomains(?string $subaccountId = null, ?string $domain = null, array $requestHeaders = [])
    {
        $params = [];
        if (null !== $subaccountId) {
            $params['subaccount_id'] = $subaccountId;
        }
        if (null !== $domain) {
            $params['domain'] = $domain;
        }

        $response = $this->httpGet('/v3/domains/dynamic_pools/assignable', $params, $requestHeaders);

        return $this->hydrateResponse($response, AssignableDomainsResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/post-v3-domains-all-dynamic-pools-enroll
     *
     * Enroll all account domains into dynamic IP pools.
     *
     * @param array $requestHeaders
     * @return MessageResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function enrollAllDomains(array $requestHeaders = [])
    {
        $response = $this->httpPost('/v3/domains/all/dynamic_pools/enroll', [], $requestHeaders);

        return $this->hydrateResponse($response, MessageResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/get-v3-dynamic-pools
     *
     * List all dynamic IP pools for the account.
     *
     * @param array $requestHeaders
     * @return IndexResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function index(array $requestHeaders = [])
    {
        $response = $this->httpGet('/v3/dynamic_pools', [], $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/post-v3-dynamic-pools-all
     *
     * Initialize or set IPs for all dynamic IP pools.
     *
     * @param array $params
     * @param array $requestHeaders
     * @return MessageResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function initializeAllPools(array $params = [], array $requestHeaders = [])
    {
        $response = $this->httpPost('/v3/dynamic_pools/all', $params, $requestHeaders);

        return $this->hydrateResponse($response, MessageResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/delete-v3-dynamic-pools-all
     *
     * Remove all dynamic IP pools from the account.
     *
     * @param array $requestHeaders
     * @return MessageResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function removeAllPools(array $requestHeaders = [])
    {
        $response = $this->httpDelete('/v3/dynamic_pools/all', [], $requestHeaders);

        return $this->hydrateResponse($response, MessageResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/post-v3-dynamic-pools-pool-name-ip
     *
     * Add an IP address to a dynamic IP pool.
     *
     * @param string $poolName
     * @param string $ip
     * @param array  $requestHeaders
     * @return MessageResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function addIpToPool(string $poolName, string $ip, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($poolName);
        Assert::ip($ip);

        $response = $this->httpPost(sprintf('/v3/dynamic_pools/%s/%s', $poolName, $ip), [], $requestHeaders);

        return $this->hydrateResponse($response, MessageResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/patch-v3-dynamic-pools-pool-name
     *
     * Update the IPs of a dynamic IP pool.
     *
     * @param string $poolName
     * @param array  $params
     * @param array  $requestHeaders
     * @return MessageResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function updatePool(string $poolName, array $params, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($poolName);

        $response = $this->httpPatch(sprintf('/v3/dynamic_pools/%s', $poolName), $params, $requestHeaders);

        return $this->hydrateResponse($response, MessageResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/get-v1-dynamic-pools-domains
     *
     * List domains assigned to dynamic IP pools.
     *
     * @param int      $limit      Maximum number of domains to return
     * @param string[] $accounts   Filter by account IDs
     * @param string[] $pools      Filter by Dynamic IP Pool names
     * @param string|null $sortBy  Sort field: 'bounce_rate', 'complaint_rate', or 'name'
     * @param string|null $sortOrder Sort direction: 'ascending' or 'descending'
     * @param array   $requestHeaders
     * @return DomainsResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function listDomains(int $limit = 100, array $accounts = [], array $pools = [], ?string $sortBy = null, ?string $sortOrder = null, array $requestHeaders = [])
    {
        $params = ['limit' => $limit];

        foreach ($accounts as $account) {
            $params['account'][] = $account;
        }
        foreach ($pools as $pool) {
            $params['pool'][] = $pool;
        }
        if (null !== $sortBy) {
            $params['sort_by'] = $sortBy;
        }
        if (null !== $sortOrder) {
            $params['sort_order'] = $sortOrder;
        }

        $response = $this->httpGet('/v1/dynamic_pools/domains', $params, $requestHeaders);

        return $this->hydrateResponse($response, DomainsResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/get-v1-dynamic-pools-domains-name-preview
     *
     * Preview the dynamic IP pool assignment for a domain.
     *
     * @param string $domain
     * @param array  $requestHeaders
     * @return DomainsResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function previewDomainAssignment(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v1/dynamic_pools/domains/%s/preview', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, DomainsResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/get-v1-dynamic-pools-domains-name-history
     *
     * List the assignment history for a domain.
     *
     * @param string $domain
     * @param array  $requestHeaders
     * @return HistoryResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function domainHistory(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v1/dynamic_pools/domains/%s/history', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, HistoryResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/put-v1-dynamic-pools-domains-name-override
     *
     * Override the dynamic IP pool assignment for a domain.
     *
     * @param string $domain
     * @param array  $params
     * @param array  $requestHeaders
     * @return MessageResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function overrideDomainAssignment(string $domain, array $params, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpPut(sprintf('/v1/dynamic_pools/domains/%s/override', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, MessageResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/delete-v1-dynamic-pools-domains-name-override
     *
     * Remove the override for a domain's dynamic IP pool assignment.
     *
     * @param string $domain
     * @param array  $requestHeaders
     * @return MessageResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function removeDomainOverride(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpDelete(sprintf('/v1/dynamic_pools/domains/%s/override', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, MessageResponse::class);
    }

    /**
     * @see https://documentation.mailgun.com/docs/mailgun/api-reference/send/mailgun/dynamic-ip-pools/get-v1-dynamic-pools-history
     *
     * List the dynamic IP pool assignment history for the account.
     *
     * @param int         $limit              Maximum number of events to return
     * @param bool        $includeSubaccounts Include events from subaccounts
     * @param string|null $domain             Filter by domain name
     * @param string|null $before             Filter events before timestamp (Mon, 02 Jan 2006 15:04:05 MST)
     * @param string|null $after              Filter events after timestamp (Mon, 02 Jan 2006 15:04:05 MST)
     * @param string|null $movedTo            Filter by destination Dynamic Pool
     * @param string|null $movedFrom          Filter by source Dynamic Pool
     * @param array       $requestHeaders
     * @return HistoryResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function accountHistory(
        int $limit = 100,
        bool $includeSubaccounts = false,
        ?string $domain = null,
        ?string $before = null,
        ?string $after = null,
        ?string $movedTo = null,
        ?string $movedFrom = null,
        array $requestHeaders = []
    ) {
        $params = [
            'Limit' => $limit,
            'include_subaccounts' => $includeSubaccounts,
        ];

        if (null !== $domain) {
            $params['domain'] = $domain;
        }
        if (null !== $before) {
            $params['before'] = $before;
        }
        if (null !== $after) {
            $params['after'] = $after;
        }
        if (null !== $movedTo) {
            $params['moved_to'] = $movedTo;
        }
        if (null !== $movedFrom) {
            $params['moved_from'] = $movedFrom;
        }

        $response = $this->httpGet('/v1/dynamic_pools/history', $params, $requestHeaders);

        return $this->hydrateResponse($response, HistoryResponse::class);
    }
}
