<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Http\Client\HttpClient;
use Mailgun\Assert;
use Mailgun\Hydrator\Hydrator;
use Mailgun\Model\Webhook\CreateResponse;
use Mailgun\Model\Webhook\DeleteResponse;
use Mailgun\Model\Webhook\IndexResponse;
use Mailgun\Model\Webhook\ShowResponse;
use Mailgun\Model\Webhook\UpdateResponse;
use Mailgun\RequestBuilder;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Webhook extends HttpApi
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @param HttpClient     $httpClient
     * @param RequestBuilder $requestBuilder
     * @param Hydrator       $hydrator
     * @param string         $apiKey
     */
    public function __construct(HttpClient $httpClient, RequestBuilder $requestBuilder, Hydrator $hydrator, $apiKey)
    {
        parent::__construct($httpClient, $requestBuilder, $hydrator);
        $this->apiKey = $apiKey;
    }

    /**
     * This function verifies the webhook signature with your API key to to see if it is authentic.
     *
     * If this function returns FALSE, you must not process the request.
     * You should reject the request with status code 403 Forbidden.
     *
     * @param int    $timestamp
     * @param string $token
     * @param string $signature
     *
     * @return bool
     */
    public function verifyWebhookSignature($timestamp, $token, $signature)
    {
        if (empty($timestamp) || empty($token) || empty($signature)) {
            return false;
        }

        $hmac = hash_hmac('sha256', $timestamp.$token, $this->apiKey);

        if (function_exists('hash_equals')) {
            // hash_equals is constant time, but will not be introduced until PHP 5.6
            return hash_equals($hmac, $signature);
        } else {
            return $hmac === $signature;
        }
    }

    /**
     * @param string $domain
     *
     * @return IndexResponse
     */
    public function index($domain)
    {
        Assert::notEmpty($domain);
        $response = $this->httpGet(sprintf('/v3/domains/%s/webhooks', $domain));

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * @param string $domain
     * @param string $webhook
     *
     * @return ShowResponse
     */
    public function show($domain, $webhook)
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($webhook);
        $response = $this->httpGet(sprintf('/v3/domains/%s/webhooks/%s', $domain, $webhook));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * @param string $domain
     * @param string $id
     * @param string $url
     *
     * @return CreateResponse
     */
    public function create($domain, $id, $url)
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($id);
        Assert::notEmpty($url);

        $params = [
            'id' => $id,
            'url' => $url,
        ];

        $response = $this->httpPost(sprintf('/v3/domains/%s/webhooks', $domain), $params);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * @param string $domain
     * @param string $id
     * @param string $url
     *
     * @return UpdateResponse
     */
    public function update($domain, $id, $url)
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($id);
        Assert::notEmpty($url);

        $params = [
            'url' => $url,
        ];

        $response = $this->httpPut(sprintf('/v3/domains/%s/webhooks/%s', $domain, $id), $params);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @param string $domain
     * @param string $id
     *
     * @return DeleteResponse
     */
    public function delete($domain, $id)
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($id);

        $response = $this->httpDelete(sprintf('/v3/domains/%s/webhooks/%s', $domain, $id));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }
}
