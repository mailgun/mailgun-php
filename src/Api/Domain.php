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
use Mailgun\Model\Domain\ConnectionResponse;
use Mailgun\Model\Domain\CreateCredentialResponse;
use Mailgun\Model\Domain\CreateResponse;
use Mailgun\Model\Domain\CredentialResponse;
use Mailgun\Model\Domain\DeleteCredentialResponse;
use Mailgun\Model\Domain\DeleteResponse;
use Mailgun\Model\Domain\IndexResponse;
use Mailgun\Model\Domain\ShowResponse;
use Mailgun\Model\Domain\TrackingResponse;
use Mailgun\Model\Domain\UpdateClickTrackingResponse;
use Mailgun\Model\Domain\UpdateConnectionResponse;
use Mailgun\Model\Domain\UpdateCredentialResponse;
use Mailgun\Model\Domain\UpdateOpenTrackingResponse;
use Mailgun\Model\Domain\UpdateUnsubscribeTrackingResponse;
use Mailgun\Model\Domain\VerifyResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://documentation.mailgun.com/api-domains.html
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class Domain extends HttpApi
{
    /**
     * Returns a list of domains on the account.
     *
     * @return IndexResponse
     */
    public function index(int $limit = 100, int $skip = 0)
    {
        Assert::range($limit, 1, 1000);

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
     * @param string $domain name of the domain
     *
     * @return ShowResponse|array|ResponseInterface
     */
    public function show(string $domain)
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
     * @see https://documentation.mailgun.com/en/latest/api-domains.html#domains
     *
     * @param string   $domain             name of the domain
     * @param string   $smtpPass           password for SMTP authentication
     * @param string   $spamAction         `disable` or `tag` - inbound spam filtering
     * @param bool     $wildcard           domain will accept email for subdomains
     * @param bool     $forceDkimAuthority force DKIM authority
     * @param string[] $ips                an array of ips to be assigned to the domain
     *
     * @return CreateResponse|array|ResponseInterface
     */
    public function create(string $domain, string $smtpPass = null, string $spamAction = null, bool $wildcard = null, bool $forceDkimAuthority = null, ?array $ips = null)
    {
        Assert::stringNotEmpty($domain);

        $params['name'] = $domain;

        if (!empty($smtpPass)) {
            Assert::stringNotEmpty($smtpPass);

            $params['smtp_password'] = $smtpPass;
        }

        if (!empty($spamAction)) {
            // TODO(sean.johnson): Extended spam filter input validation.
            Assert::stringNotEmpty($spamAction);

            $params['spam_action'] = $spamAction;
        }

        if (null !== $wildcard) {
            Assert::boolean($wildcard);

            $params['wildcard'] = $wildcard ? 'true' : 'false';
        }

        if (null !== $forceDkimAuthority) {
            Assert::boolean($forceDkimAuthority);

            $params['force_dkim_authority'] = $forceDkimAuthority ? 'true' : 'false';
        }

        if (null !== $ips) {
            Assert::isList($ips);
            Assert::allString($ips);

            $params['ips'] = join(',', $ips);
        }

        $response = $this->httpPost('/v3/domains', $params);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * Removes a domain from the account.
     * WARNING: This action is irreversible! Be cautious!
     *
     * @param string $domain name of the domain
     *
     * @return DeleteResponse|array|ResponseInterface
     */
    public function delete(string $domain)
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpDelete(sprintf('/v3/domains/%s', $domain));

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * Returns a list of SMTP credentials for the specified domain.
     *
     * @param string $domain name of the domain
     * @param int    $limit  Number of credentials to return
     * @param int    $skip   Number of credentials to omit from the list
     *
     * @return CredentialResponse
     */
    public function credentials(string $domain, int $limit = 100, int $skip = 0)
    {
        Assert::stringNotEmpty($domain);
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
     * @param string $domain   name of the domain
     * @param string $login    SMTP Username
     * @param string $password SMTP Password. Length min 5, max 32.
     *
     * @return CreateCredentialResponse|array|ResponseInterface
     */
    public function createCredential(string $domain, string $login, string $password)
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
     * @param string $domain name of the domain
     * @param string $login  SMTP Username
     * @param string $pass   New SMTP Password. Length min 5, max 32.
     *
     * @return UpdateCredentialResponse|array|ResponseInterface
     */
    public function updateCredential(string $domain, string $login, string $pass)
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
     * @param string $domain name of the domain
     * @param string $login  SMTP Username
     *
     * @return DeleteCredentialResponse|array|ResponseInterface
     */
    public function deleteCredential(string $domain, string $login)
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
     * @param string $domain name of the domain
     *
     * @return ConnectionResponse|ResponseInterface
     */
    public function connection(string $domain)
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/domains/%s/connection', $domain));

        return $this->hydrateResponse($response, ConnectionResponse::class);
    }

    /**
     * Updates the specified delivery connection settings for the specified domain.
     * If a parameter is passed in as null, it will not be updated.
     *
     * @param string    $domain     name of the domain
     * @param bool|null $requireTLS enforces that messages are sent only over a TLS connection
     * @param bool|null $noVerify   disables TLS certificate and hostname verification
     *
     * @return UpdateConnectionResponse|array|ResponseInterface
     */
    public function updateConnection(string $domain, ?bool $requireTLS, ?bool $noVerify)
    {
        Assert::stringNotEmpty($domain);
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

    /**
     * Returns a single domain.
     *
     * @param string $domain name of the domain
     *
     * @return VerifyResponse|array|ResponseInterface
     */
    public function verify(string $domain)
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpPut(sprintf('/v3/domains/%s/verify', $domain));

        return $this->hydrateResponse($response, VerifyResponse::class);
    }

    /**
     * Returns a domain tracking settings.
     *
     * @param string $domain name of the domain
     *
     * @return TrackingResponse|array|ResponseInterface
     */
    public function tracking(string $domain)
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/domains/%s/tracking', $domain));

        return $this->hydrateResponse($response, TrackingResponse::class);
    }

    /**
     * Updates a domain click tracking settings.
     *
     * @param string $domain The name of the domain
     * @param string $active The status for this tracking (one of: yes, no)
     *
     * @return UpdateClickTrackingResponse|array|ResponseInterface
     *
     * @throws \Exception
     */
    public function updateClickTracking(string $domain, string $active)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($active);
        Assert::oneOf($active, ['yes', 'no', 'htmlonly']);

        $params = [
            'active' => $active,
        ];

        $response = $this->httpPut(sprintf('/v3/domains/%s/tracking/click', $domain), $params);

        return $this->hydrateResponse($response, UpdateClickTrackingResponse::class);
    }

    /**
     * Updates a domain open tracking settings.
     *
     * @param string $domain The name of the domain
     * @param string $active The status for this tracking (one of: yes, no)
     *
     * @return UpdateOpenTrackingResponse|array|ResponseInterface
     */
    public function updateOpenTracking(string $domain, string $active)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($active);
        Assert::oneOf($active, ['yes', 'no']);

        $params = [
            'active' => $active,
        ];

        $response = $this->httpPut(sprintf('/v3/domains/%s/tracking/open', $domain), $params);

        return $this->hydrateResponse($response, UpdateOpenTrackingResponse::class);
    }

    /**
     * Updates a domain unsubscribe tracking settings.
     *
     * @param string $domain     The name of the domain
     * @param string $active     The status for this tracking (one of: yes, no)
     * @param string $htmlFooter The footer for HTML emails
     * @param string $textFooter The footer for plain text emails
     *
     * @return UpdateUnsubscribeTrackingResponse|array|ResponseInterface
     *
     * @throws \Exception
     */
    public function updateUnsubscribeTracking(string $domain, string $active, string $htmlFooter, string $textFooter)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($active);
        Assert::oneOf($active, ['yes', 'no', 'true', 'false']);
        Assert::stringNotEmpty($htmlFooter);
        Assert::nullOrString($textFooter);

        $params = [
            'active' => (in_array($active, ['yes', 'true'], true)) ? 'true' : 'false',
            'html_footer' => $htmlFooter,
            'text_footer' => $textFooter,
        ];

        $response = $this->httpPut(sprintf('/v3/domains/%s/tracking/unsubscribe', $domain), $params);

        return $this->hydrateResponse($response, UpdateUnsubscribeTrackingResponse::class);
    }
}
