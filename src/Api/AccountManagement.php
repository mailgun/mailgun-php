<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Model\AccountManagement\AccountResponse;
use Mailgun\Model\AccountManagement\HttpSigningKeyResponse;
use Mailgun\Model\AccountManagement\SandboxAuthRecipientsResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

class AccountManagement extends HttpApi
{
    /**
     * Updates account settings.
     * @param array $params
     * @param array $requestHeaders
     * @return AccountResponse|array|ResponseInterface
     * @throws ClientExceptionInterface|\JsonException
     * @throws \Exception
     */
    public function updateAccountSettings(array $params, array $requestHeaders = [])
    {
        $response = $this->httpPut('/v5/accounts', $params, $requestHeaders);

        return $this->hydrateResponse($response, AccountResponse::class);
    }

    /**
     * Retrieves the HTTP signing key.
     * @param array $requestHeaders
     * @return HttpSigningKeyResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws \Exception
     */
    public function getHttpSigningKey(array $requestHeaders = [])
    {
        $response = $this->httpGet('/v5/accounts/http_signing_key', [], $requestHeaders);

        return $this->hydrateResponse($response, HttpSigningKeyResponse::class);
    }

    /**
     * Creates a new HTTP signing key.
     * @param array $requestHeaders
     * @return HttpSigningKeyResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     * @throws \Exception
     */
    public function createHttpSigningKey(array $requestHeaders = [])
    {
        $response = $this->httpPost('/v5/accounts/http_signing_key', [], $requestHeaders);

        return $this->hydrateResponse($response, HttpSigningKeyResponse::class);
    }

    /**
     * Retrieves the list of sandbox authorized recipients.
     * @param array $requestHeaders
     * @return SandboxAuthRecipientsResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws \Exception
     */
    public function getSandboxAuthRecipients(array $requestHeaders = [])
    {
        $response = $this->httpGet('/v5/sandbox/auth_recipients', [], $requestHeaders);

        return $this->hydrateResponse($response, SandboxAuthRecipientsResponse::class);
    }

    /**
     * Add authorized email recipient for a sandbox domain
     * @param string $email
     * @param array $requestHeaders
     * @return SandboxAuthRecipientsResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     * @throws \Exception
     */
    public function addRecipientSandbox(string $email, array $requestHeaders = [])
    {
        $response = $this->httpPost('/v5/sandbox/auth_recipients', ['email' => $email], $requestHeaders);

        return $this->hydrateResponse($response, SandboxAuthRecipientsResponse::class);
    }
}
