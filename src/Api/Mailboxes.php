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
use Mailgun\Model\Mailboxes\CreateResponse;
use Mailgun\Model\Mailboxes\DeleteResponse;
use Mailgun\Model\Mailboxes\ShowResponse;
use Mailgun\Model\Mailboxes\UpdateResponse;

class Mailboxes extends HttpApi
{
    private const MIN_PASSWORD_LENGTH = 5;

    /**
     * @param string $domain
     * @param array  $parameters
     *
     * @return CreateResponse
     *
     * @throws \Exception
     */
    public function create(string $domain, array $parameters = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::keyExists($parameters, 'mailbox');
        Assert::keyExists($parameters, 'password');
        Assert::minLength($parameters['password'], self::MIN_PASSWORD_LENGTH);

        $response = $this->httpPost(sprintf('/v3/%s/mailboxes', $domain), $parameters);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * @param string $domain
     * @param array  $parameters
     *
     * @return ShowResponse
     *
     * @throws \Exception
     */
    public function show(string $domain, array $parameters = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/%s/mailboxes', $domain), $parameters);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * @param string $domain
     * @param string $mailbox
     * @param array  $parameters
     *
     * @return UpdateResponse
     *
     * @throws \Exception
     */
    public function update(string $domain, string $mailbox, array $parameters = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($mailbox);

        $response = $this->httpPut(sprintf('/v3/%s/mailboxes/%s', $domain, $mailbox), $parameters);

        return $this->hydrateResponse($response, UpdateResponse::class);
    }

    /**
     * @param string $domain
     * @param string $mailbox
     *
     * @return DeleteResponse
     *
     * @throws \Exception
     */
    public function delete(string $domain, string $mailbox)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($mailbox);

        $response = $this->httpDelete(sprintf('/v3/%s/mailboxes/%s', $domain, $mailbox));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }
}
