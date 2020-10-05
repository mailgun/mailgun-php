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
     *
     * @return IndexResponse|ResponseInterface
     */
    public function index(?bool $dedicated = null)
    {
        $params = [];
        if (null !== $dedicated) {
            Assert::boolean($dedicated);
            $params['dedicated'] = $dedicated;
        }

        $response = $this->httpGet('/v3/ips', $params);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a list of IPs assigned to a domain.
     *
     * @return IndexResponse|ResponseInterface
     */
    public function domainIndex(string $domain)
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/domains/%s/ips', $domain));

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a single ip.
     *
     * @return ShowResponse|ResponseInterface
     */
    public function show(string $ip)
    {
        Assert::ip($ip);

        $response = $this->httpGet(sprintf('/v3/ips/%s', $ip));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Assign a dedicated IP to the domain specified.
     *
     * @return UpdateResponse|ResponseInterface
     */
    public function assign(string $domain, string $ip)
    {
        Assert::stringNotEmpty($domain);
        Assert::ip($ip);

        $params = [
            'ip' => $ip,
        ];

        $response = $this->httpPost(sprintf('/v3/domains/%s/ips', $domain), $params);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * Unassign an IP from the domain specified.
     *
     * @return UpdateResponse|ResponseInterface
     */
    public function unassign(string $domain, string $ip)
    {
        Assert::stringNotEmpty($domain);
        Assert::ip($ip);

        $response = $this->httpDelete(sprintf('/v3/domains/%s/ips/%s', $domain, $ip));

        return $this->hydrateResponse($response, UpdateResponse::class);
    }
}
