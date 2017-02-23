<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Model\Webhook\CreateResponse;
use Mailgun\Model\Webhook\DeleteResponse;
use Mailgun\Model\Webhook\IndexResponse;
use Mailgun\Model\Webhook\ShowResponse;
use Mailgun\Model\Webhook\UpdateResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Webhook extends HttpApi
{
    /**
     * @param string $domain
     *
     * @return IndexResponse
     */
    public function index($domain)
    {
        Assert::notEmpty($domain);
        $response = $this->httpGet(sprintf('/v3/domains/%s/webhooks', $domain));

        return $this->safeDeserialize($response, IndexResponse::class);
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

        return $this->safeDeserialize($response, ShowResponse::class);
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

        return $this->safeDeserialize($response, CreateResponse::class);
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

        return $this->safeDeserialize($response, UpdateResponse::class);
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

        return $this->safeDeserialize($response, DeleteResponse::class);
    }
}
