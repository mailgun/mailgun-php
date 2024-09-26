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
use Mailgun\Model\Metrics\MetricsResponse;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @see https://documentation.mailgun.com/docs/mailgun/api-reference/openapi-final/tag/Metrics/
 */
class Metrics extends HttpApi
{
    /**
     * Query metrics for the total account.
     *
     * @param array $payload
     * @param array $requestHeaders
     * @return MetricsResponse
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function loadMetrics(array $payload = [], array $requestHeaders = []): MetricsResponse
    {
        // Validating required params
        if (!isset($payload['start']) || !isset($payload['end'])) {
            throw new Exception("The 'start' and 'end' parameters are required.");
        }

        // Ensure start and end date are in RFC 2822 format
        Assert::string($payload['start'], "Start date must be in RFC 2822 format");
        Assert::stringNotEmpty($payload['end'], "End date must be in RFC 2822 format");

        // Ensure resolution is valid (day, hour, month)
        if (!empty($payload['resolution'])) {
            Assert::oneOf($payload['resolution'], ['day', 'hour', 'month'], 'Invalid resolution format');
        }

        // Check if filters are properly set up
        if (!empty($payload['filter']['AND'])) {
            foreach ($payload['filter']['AND'] as $filter) {
                Assert::stringNotEmpty($filter['attribute'], "Filter attribute must be specified");
                Assert::stringNotEmpty($filter['comparator'], "Comparator must be specified");
                Assert::isArray($filter['values'], "Filter values must be an array");
            }
        }

        // Validate dimensions (must be an array and contain only valid values)
        if (isset($payload['dimensions'])) {
            Assert::isArray($payload['dimensions'], 'Dimensions must be an array');
            $validDimensions = ['time', 'domain', 'ip', 'ip_pool', 'recipient_domain', 'tag', 'country', 'subaccount'];
            foreach ($payload['dimensions'] as $dimension) {
                Assert::oneOf($dimension, $validDimensions, "Invalid dimension: $dimension");
            }
        }

        $response = $this->httpPost('/v1/analytics/metrics', $payload, $requestHeaders);

        return $this->hydrateResponse($response, MetricsResponse::class);
    }
}
