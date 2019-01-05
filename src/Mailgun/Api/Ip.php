<?php

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
use Psr\Http\Message\ResponseInterface;

/**
 * {@link https://documentation.mailgun.com/en/latest/api-ips.html#ips}.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Ip extends HttpApi
{
    /**
     * Returns a list of IPs.
     *
     * @param bool $dedicated
     *
     * @return IndexResponse|ResponseInterface
     */
    public function index($dedicated = false)
    {
        Assert::boolean($dedicated);

        $params = [
            'dedicated' => $dedicated,
        ];

        $response = $this->httpGet('/v3/ips', $params);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a list of IPs assigned to a domain.
     *
     * @param string $domain
     *
     * @return IndexResponse|ResponseInterface
     */
    public function domainIndex($domain)
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/domains/%s/ip', $domain));

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a single ip.
     *
     * @param string $ip
     *
     * @return ShowResponse|ResponseInterface
     */
    public function show($ip)
    {
        Assert::ip($ip);

        $response = $this->httpGet(sprintf('/v3/ips/%s', $ip));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Assign a dedicated IP to the domain specified.
     *
     * @param string $domain
     * @param string $ip
     *
     * @return UpdateResponse|ResponseInterface
     */
    public function assign($domain, $ip)
    {
        Assert::stringNotEmpty($domain);
        Assert::ip($ip);

        $params = [
            'id' => $ip,
        ];

        $response = $this->httpPost(sprintf('/v3/domains/%s/ips', $domain), $params);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Unassign an IP from the domain specified.
     *
     * @param string $domain
     * @param string $ip
     *
     * @return UpdateResponse|ResponseInterface
     */
    public function unassign($domain, $ip)
    {
        Assert::stringNotEmpty($domain);
        Assert::ip($ip);

        $response = $this->httpDelete(sprintf('/v3/domains/%s/ips/%s', $domain, $ip));

        return $this->hydrateResponse($response, UpdateResponse::class);
    }
}
