<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Model\Domain\ConnectionResponse;
use Mailgun\Model\Domain\CreateCredentialResponse;
use Mailgun\Model\Domain\CreateResponse;
use Mailgun\Model\Domain\CredentialResponse;
use Mailgun\Model\Domain\DeleteCredentialResponse;
use Mailgun\Model\Domain\DeleteResponse;
use Mailgun\Model\Domain\IndexResponse;
use Mailgun\Model\Domain\ShowResponse;
use Mailgun\Model\Domain\UpdateConnectionResponse;
use Mailgun\Model\Domain\UpdateCredentialResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * {@link https://documentation.mailgun.com/api-domains.html}.
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class Domain extends HttpApi
{
    /**
     * Returns a list of domains on the account.
     *
     * @param int $limit
     * @param int $skip
     *
     * @return IndexResponse
     */
    public function index($limit = 100, $skip = 0)
    {
        Assert::integer($limit);
        Assert::integer($skip);

        $params = [
            'limit' => $limit,
            'skip' => $skip,
        ];

        $response = $this->httpGet('/v3/domains', $params);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a single domain.
     *
     * @param string $domain Name of the domain.
     *
     * @return ShowResponse|array|ResponseInterface
     */
    public function show($domain)
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/domains/%s', $domain));

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Creates a new domain for the account.
     * See below for spam filtering parameter information.
     * {@link https://documentation.mailgun.com/user_manual.html#um-spam-filter}.
     *
     * @param string $domain     Name of the domain.
     * @param string $smtpPass   Password for SMTP authentication.
     * @param string $spamAction `disable` or `tag` - inbound spam filtering.
     * @param bool   $wildcard   Domain will accept email for subdomains.
     *
     * @return CreateResponse|array|ResponseInterface
     */
    public function create($domain, $smtpPass, $spamAction, $wildcard)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($smtpPass);
        // TODO(sean.johnson): Extended spam filter input validation.
        Assert::stringNotEmpty($spamAction);
        Assert::boolean($wildcard);

        $params = [
            'name' => $domain,
            'smtp_password' => $smtpPass,
            'spam_action' => $spamAction,
            'wildcard' => $wildcard,
        ];

        $response = $this->httpPost('/v3/domains', $params);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * Removes a domain from the account.
     * WARNING: This action is irreversible! Be cautious!
     *
     * @param string $domain Name of the domain.
     *
     * @return DeleteResponse|array|ResponseInterface
     */
    public function delete($domain)
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpDelete(sprintf('/v3/domains/%s', $domain));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * Returns a list of SMTP credentials for the specified domain.
     *
     * @param string $domain Name of the domain.
     * @param int    $limit  Number of credentials to return
     * @param int    $skip   Number of credentials to omit from the list
     *
     * @return CredentialResponse
     */
    public function credentials($domain, $limit = 100, $skip = 0)
    {
        Assert::stringNotEmpty($domain);
        Assert::integer($limit);
        Assert::integer($skip);

        $params = [
            'limit' => $limit,
            'skip' => $skip,
        ];

        $response = $this->httpGet(sprintf('/v3/domains/%s/credentials', $domain), $params);

        return $this->hydrateResponse($response, CredentialResponse::class);
    }

    /**
     * Create a new SMTP credential pair for the specified domain.
     *
     * @param string $domain   Name of the domain.
     * @param string $login    SMTP Username.
     * @param string $password SMTP Password. Length min 5, max 32.
     *
     * @return CreateCredentialResponse|array|ResponseInterface
     */
    public function createCredential($domain, $login, $password)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($login);
        Assert::stringNotEmpty($password);
        Assert::lengthBetween($password, 5, 32, 'SMTP password must be between 5 and 32 characters.');

        $params = [
            'login' => $login,
            'password' => $password,
        ];

        $response = $this->httpPost(sprintf('/v3/domains/%s/credentials', $domain), $params);

        return $this->hydrateResponse($response, CreateCredentialResponse::class);
    }

    /**
     * Update a set of SMTP credentials for the specified domain.
     *
     * @param string $domain Name of the domain.
     * @param string $login  SMTP Username.
     * @param string $pass   New SMTP Password. Length min 5, max 32.
     *
     * @return UpdateCredentialResponse|array|ResponseInterface
     */
    public function updateCredential($domain, $login, $pass)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($login);
        Assert::stringNotEmpty($pass);
        Assert::lengthBetween($pass, 5, 32, 'SMTP password must be between 5 and 32 characters.');

        $params = [
            'password' => $pass,
        ];

        $response = $this->httpPut(sprintf('/v3/domains/%s/credentials/%s', $domain, $login), $params);

        return $this->hydrateResponse($response, UpdateCredentialResponse::class);
    }

    /**
     * Remove a set of SMTP credentials from the specified domain.
     *
     * @param string $domain Name of the domain.
     * @param string $login  SMTP Username.
     *
     * @return DeleteCredentialResponse|array|ResponseInterface
     */
    public function deleteCredential($domain, $login)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($login);

        $response = $this->httpDelete(
            sprintf(
                '/v3/domains/%s/credentials/%s',
                $domain,
                $login
            )
        );

        return $this->hydrateResponse($response, DeleteCredentialResponse::class);
    }

    /**
     * Returns delivery connection settings for the specified domain.
     *
     * @param string $domain Name of the domain.
     *
     * @return ConnectionResponse|ResponseInterface
     */
    public function connection($domain)
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/domains/%s/connection', $domain));

        return $this->hydrateResponse($response, ConnectionResponse::class);
    }

    /**
     * Updates the specified delivery connection settings for the specified domain.
     * If a parameter is passed in as null, it will not be updated.
     *
     * @param string    $domain     Name of the domain.
     * @param bool|null $requireTLS Enforces that messages are sent only over a TLS connection.
     * @param bool|null $noVerify   Disables TLS certificate and hostname verification.
     *
     * @return UpdateConnectionResponse|array|ResponseInterface
     */
    public function updateConnection($domain, $requireTLS, $noVerify)
    {
        Assert::stringNotEmpty($domain);
        Assert::nullOrBoolean($requireTLS);
        Assert::nullOrBoolean($noVerify);

        $params = [];

        if (null !== $requireTLS) {
            $params['require_tls'] = $requireTLS ? 'true' : 'false';
        }

        if (null !== $noVerify) {
            $params['skip_verification'] = $noVerify ? 'true' : 'false';
        }

        $response = $this->httpPut(sprintf('/v3/domains/%s/connection', $domain), $params);

        return $this->hydrateResponse($response, UpdateConnectionResponse::class);
    }
}
