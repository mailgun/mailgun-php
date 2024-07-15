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
use Mailgun\Model\Stats\AggregateCountriesResponse;
use Mailgun\Model\Stats\AggregateDevicesResponse;
use Mailgun\Model\Stats\AggregateResponse;
use Mailgun\Model\Stats\TotalResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-stats.html
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Stats extends HttpApi
{
    public const EVENT_ACCEPTED = 'accepted';
    public const EVENT_DELIVERED = 'delivered';
    public const EVENT_FAILED = 'failed';
    public const EVENT_OPENED = 'opened';
    public const EVENT_CLICKED = 'clicked';
    public const EVENT_UNSUBSCRIBED = 'unsubscribed';
    public const EVENT_COMPLAINED = 'complained';
    public const EVENT_STORED = 'stored';

    /**
     * @param string $domain
     * @param array $params
     * @param array $requestHeaders
     * @return TotalResponse|array
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function total(string $domain, array $params = [], array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/%s/stats/total', rawurlencode($domain)), $params, $requestHeaders);

        return $this->hydrateResponse($response, TotalResponse::class);
    }

    /**
     * @param array $params
     * @param array $requestHeaders
     * @return TotalResponse|array
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function totalAccount(array $params = [], array $requestHeaders = [])
    {
        Assert::keyExists($params, 'event', 'You must specify an event');

        $response = $this->httpGet('/v3/stats/total', $params, $requestHeaders);

        return $this->hydrateResponse($response, TotalResponse::class);
    }

    /**
     * @param string $domain
     * @param array $requestHeaders
     * @return AggregateResponse
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function aggregateCountsByESP(string $domain, array $requestHeaders = []): AggregateResponse
    {
        $response = $this->httpGet(sprintf('/v3/%s/aggregates/providers', rawurlencode($domain)), [], $requestHeaders);

        return $this->hydrateResponse($response, AggregateResponse::class);
    }

    /**
     * @param string $domain
     * @param array $requestHeaders
     * @return AggregateDevicesResponse
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function aggregateByDevices(string $domain, array $requestHeaders = []): AggregateDevicesResponse
    {
        $response = $this->httpGet(sprintf('/v3/%s/aggregates/devices', rawurlencode($domain)), [], $requestHeaders);

        return $this->hydrateResponse($response, AggregateDevicesResponse::class);
    }

    /**
     * @param string $domain
     * @param array $requestHeaders
     * @return AggregateCountriesResponse
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function aggregateByCountry(string $domain, array $requestHeaders = []): AggregateCountriesResponse
    {
        $response = $this->httpGet(sprintf('/v3/%s/aggregates/countries', rawurlencode($domain)), [], $requestHeaders);

        return $this->hydrateResponse($response, AggregateCountriesResponse::class);
    }
}
