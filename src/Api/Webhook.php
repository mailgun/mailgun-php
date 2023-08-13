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
use Mailgun\HttpClient\RequestBuilder;
use Mailgun\Hydrator\Hydrator;
use Mailgun\Model\Webhook\CreateResponse;
use Mailgun\Model\Webhook\DeleteResponse;
use Mailgun\Model\Webhook\IndexResponse;
use Mailgun\Model\Webhook\ShowResponse;
use Mailgun\Model\Webhook\UpdateResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-webhooks.html
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Webhook extends HttpApi
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @param ClientInterface $httpClient
     * @param RequestBuilder  $requestBuilder
     * @param Hydrator        $hydrator
     * @param string          $apiKey
     */
    public function __construct($httpClient, RequestBuilder $requestBuilder, Hydrator $hydrator, string $apiKey)
    {
        parent::__construct($httpClient, $requestBuilder, $hydrator);
        $this->apiKey = $apiKey;
    }

    /**
     * This function verifies the webhook signature with your API key to to see if it is authentic.
     * If this function returns FALSE, you must not process the request.
     * You should reject the request with status code 403 Forbidden.
     *
     * @param  int    $timestamp
     * @param  string $token
     * @param  string $signature
     * @return bool
     */
    public function verifyWebhookSignature(int $timestamp, string $token, string $signature): bool
    {
        if (empty($timestamp) || empty($token) || empty($signature)) {
            return false;
        }

        $hmac = hash_hmac('sha256', $timestamp.$token, $this->apiKey);

        if (function_exists('hash_equals')) {
            // hash_equals is constant time, but will not be introduced until PHP 5.6
            return hash_equals($hmac, $signature);
        }

        return $hmac === $signature;
    }

    /**
     * @param  string                          $domain
     * @return IndexResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function index(string $domain)
    {
        Assert::notEmpty($domain);
        $response = $this->httpGet(sprintf('/v3/domains/%s/webhooks', $domain));

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * @param  string                         $domain
     * @param  string                         $webhook
     * @return ShowResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function show(string $domain, string $webhook)
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($webhook);
        $response = $this->httpGet(sprintf('/v3/domains/%s/webhooks/%s', $domain, $webhook));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * @param  string                           $domain
     * @param  string                           $id
     * @param  array                            $url
     * @return CreateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function create(string $domain, string $id, array $url)
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
     * @param  string                           $domain
     * @param  string                           $id
     * @param  array                            $url
     * @return UpdateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function update(string $domain, string $id, array $url)
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
     * @param  string                           $domain
     * @param  string                           $id
     * @return DeleteResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function delete(string $domain, string $id)
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($id);

        $response = $this->httpDelete(sprintf('/v3/domains/%s/webhooks/%s', $domain, $id));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }
}
