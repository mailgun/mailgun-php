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
use Mailgun\Model\Stats\TotalResponse;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-stats.html
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Stats extends HttpApi
{
    /**
     * @param  string                   $domain
     * @param  array                    $params
     * @param  array                    $requestHeaders
     * @return TotalResponse|array
     * @throws ClientExceptionInterface
     */
    public function total(string $domain, array $params = [], array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/%s/stats/total', rawurlencode($domain)), $params, $requestHeaders);

        return $this->hydrateResponse($response, TotalResponse::class);
    }
}
