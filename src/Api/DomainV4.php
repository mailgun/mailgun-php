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
use Mailgun\Model\Domain\WebPrefixResponse;
use Mailgun\Model\Domain\WebSchemeResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-domains.html
 */
class DomainV4 extends HttpApi
{
    private const DKIM_SIZES = ['1024', '2048'];

    /**
     * Returns a list of domains on the account.
     * @param  int                      $limit
     * @param  int                      $skip
     * @param  array                    $requestHeaders
     * @return IndexResponse|array
     * @throws ClientExceptionInterface
     */
    public function index(int $limit = 100, int $skip = 0, array $requestHeaders = [])
    {
        Assert::range($limit, 1, 1000);

        $params = [
            'limit' => $limit,
            'skip' => $skip,
        ];

        $response = $this->httpGet('/v4/domains', $params, $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * Returns a single domain.
     * @param  string                               $domain         name of the domain
     * @param  array                                $requestHeaders
     * @return ShowResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function show(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v4/domains/%s', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * Creates a new domain for the account.
     * See below for spam filtering parameter information.
     * {@link https://documentation.mailgun.com/en/latest/user_manual.html#um-spam-filter}.
     * @see    https://documentation.mailgun.com/en/latest/api-domains.html#domains
     * @param string $domain name of the domain
     * @param string|null $smtpPass password for SMTP authentication
     * @param string|null $spamAction `disable` or `tag` - inbound spam filtering
     * @param bool|null $wildcard
     * @param bool|null $forceDkimAuthority
     * @param string[] $ips an array of ips to be assigned to the domain
     * @param ?string $pool_id pool id to assign to the domain
     * @param string $webScheme `http` or `https` - set your open, click and unsubscribe URLs to use http or https. The default is http
     * @param string $dkimKeySize Set length of your domain’s generated DKIM key
     * @param array $requestHeaders
     * @param string|null $dkimHostName
     * @param string|null $dkimSelector
     * @return CreateResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function create(
        string  $domain,
        string  $smtpPass = null,
        string  $spamAction = null,
        ?bool $wildcard = null,
        ?bool $forceDkimAuthority = null,
        ?array  $ips = null,
        ?string $pool_id = null,
        string  $webScheme = 'http',
        string  $dkimKeySize = '1024',
        array   $requestHeaders = [],
        ?string $dkimHostName = null,
        ?string $dkimSelector = null,

    ) {
        Assert::stringNotEmpty($domain);

        $params = [];

        $params['name'] = $domain;

        if (!empty($smtpPass)) {
            Assert::stringNotEmpty($smtpPass);

            $params['smtp_password'] = $smtpPass;
        }

        if (!empty($spamAction)) {
            Assert::stringNotEmpty($spamAction);

            $params['spam_action'] = $spamAction;
        }

        if (null !== $wildcard) {
            $params['wildcard'] = $wildcard ? 'true' : 'false';
        }

        if (null !== $forceDkimAuthority) {
            $params['force_dkim_authority'] = $forceDkimAuthority ? 'true' : 'false';
        }

        if (null !== $ips) {
            Assert::isList($ips);
            Assert::allString($ips);

            $params['ips'] = implode(',', $ips);
        }

        if (!empty($webScheme)) {
            Assert::stringNotEmpty($webScheme);
            Assert::oneOf($webScheme, ['https', 'http']);
            $params['web_scheme'] = $webScheme;
        }

        if (null !== $pool_id) {
            Assert::stringNotEmpty($pool_id);

            $params['pool_id'] = $pool_id;
        }
        if (!empty($dkimKeySize)) {
            Assert::oneOf(
                $dkimKeySize,
                self::DKIM_SIZES,
                'Length of your domain’s generated DKIM key must be 1024 or 2048'
            );
            $params['dkim_key_size'] = $dkimKeySize;
        }

        if (!empty($dkimHostName)) {
            Assert::stringNotEmpty($dkimHostName);
            $params['dkim_host_name'] = $dkimHostName;
        }

        if (!empty($dkimSelector)) {
            Assert::stringNotEmpty($dkimSelector);
            $params['dkim_selector'] = $dkimSelector;
        }

        $response = $this->httpPost('/v4/domains', $params, $requestHeaders);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * Removes a domain from the account.
     * WARNING: This action is irreversible! Be cautious!
     * @param string $domain name of the domain
     * @param array $requestHeaders
     * @return DeleteResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function delete(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpDelete(sprintf('/v4/domains/%s', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, DeleteResponse::class);
    }

    /**
     * Returns a list of SMTP credentials for the specified domain.
     * @param  string                   $domain         name of the domain
     * @param  int                      $limit          Number of credentials to return
     * @param  int                      $skip           Number of credentials to omit from the list
     * @param  array                    $requestHeaders
     * @return CredentialResponse
     * @throws ClientExceptionInterface
     */
    public function credentials(string $domain, int $limit = 100, int $skip = 0, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        $params = [
            'limit' => $limit,
            'skip' => $skip,
        ];

        $response = $this->httpGet(sprintf('/v3/domains/%s/credentials', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, CredentialResponse::class);
    }

    /**
     * Create a new SMTP credential pair for the specified domain.
     * @param  string                                           $domain         name of the domain
     * @param  string                                           $login          SMTP Username
     * @param  string                                           $password       SMTP Password. Length min 5, max 32.
     * @param  array                                            $requestHeaders
     * @return CreateCredentialResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function createCredential(string $domain, string $login, string $password, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($login);
        Assert::stringNotEmpty($password);
        Assert::lengthBetween($password, 5, 32, 'SMTP password must be between 5 and 32 characters.');

        $params = [
            'login' => $login,
            'password' => $password,
        ];

        $response = $this->httpPost(sprintf('/v3/domains/%s/credentials', $domain), $params, $requestHeaders);

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
     * @throws ClientExceptionInterface
     */
    public function updateCredential(string $domain, string $login, string $pass, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($login);
        Assert::stringNotEmpty($pass);
        Assert::lengthBetween($pass, 5, 32, 'SMTP password must be between 5 and 32 characters.');

        $params = [
            'password' => $pass,
        ];

        $response = $this->httpPut(sprintf('/v3/domains/%s/credentials/%s', $domain, $login), $params, $requestHeaders);

        return $this->hydrateResponse($response, UpdateCredentialResponse::class);
    }

    /**
     * Remove a set of SMTP credentials from the specified domain.
     * @param  string                                           $domain         name of the domain
     * @param  string                                           $login          SMTP Username
     * @param  array                                            $requestHeaders
     * @return DeleteCredentialResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function deleteCredential(string $domain, string $login, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($login);

        $response = $this->httpDelete(
            sprintf(
                '/v3/domains/%s/credentials/%s',
                $domain,
                $login
            ),
            [],
            $requestHeaders
        );

        return $this->hydrateResponse($response, DeleteCredentialResponse::class);
    }

    /**
     * Returns delivery connection settings for the specified domain.
     * @param  string                               $domain         name of the domain
     * @param  array                                $requestHeaders
     * @return ConnectionResponse|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function connection(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/domains/%s/connection', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, ConnectionResponse::class);
    }

    /**
     * Updates the specified delivery connection settings for the specified domain.
     * If a parameter is passed in as null, it will not be updated.
     * @param  string                                           $domain         name of the domain
     * @param  bool|null                                        $requireTLS     enforces that messages are sent only over a TLS connection
     * @param  bool|null                                        $noVerify       disables TLS certificate and hostname verification
     * @param  array                                            $requestHeaders
     * @return UpdateConnectionResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function updateConnection(string $domain, ?bool $requireTLS, ?bool $noVerify, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        $params = [];

        if (null !== $requireTLS) {
            $params['require_tls'] = $requireTLS ? 'true' : 'false';
        }

        if (null !== $noVerify) {
            $params['skip_verification'] = $noVerify ? 'true' : 'false';
        }

        $response = $this->httpPut(sprintf('/v3/domains/%s/connection', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, UpdateConnectionResponse::class);
    }

    /**
     * Update webScheme for existing domain
     * See below for spam filtering parameter information.
     * {@link https://documentation.mailgun.com/en/latest/user_manual.html#um-spam-filter}.
     * @see https://documentation.mailgun.com/en/latest/api-domains.html#domains
     * @param  string                                    $domain         name of the domain
     * @param  string                                    $webScheme      `http` or `https` - set your open, click and unsubscribe URLs to use http or https. The default is http
     * @param  array                                     $requestHeaders
     * @return WebSchemeResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function updateWebScheme(string $domain, string $webScheme = 'http', array $requestHeaders = [])
    {
        $params = [];
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($webScheme);
        Assert::oneOf($webScheme, ['https', 'http']);

        $params['web_scheme'] = $webScheme;

        $response = $this->httpPut(sprintf('/v4/domains/%s', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, WebSchemeResponse::class);
    }

    /**
     * Returns a single domain.
     * @param  string                                 $domain         name of the domain
     * @param  array                                  $requestHeaders
     * @return VerifyResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function verify(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpPut(sprintf('/v4/domains/%s/verify', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, VerifyResponse::class);
    }

    /**
     * Returns a domain tracking settings.
     * @param  string                                   $domain         name of the domain
     * @param  array                                    $requestHeaders
     * @return TrackingResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function tracking(string $domain, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);

        $response = $this->httpGet(sprintf('/v3/domains/%s/tracking', $domain), [], $requestHeaders);

        return $this->hydrateResponse($response, TrackingResponse::class);
    }

    /**
     * Updates a domain click tracking settings.
     * @param  string                                              $domain         The name of the domain
     * @param  string                                              $active         The status for this tracking (one of: yes, no)
     * @param  array                                               $requestHeaders
     * @return UpdateClickTrackingResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function updateClickTracking(string $domain, string $active, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($active);
        Assert::oneOf($active, ['yes', 'no', 'htmlonly']);

        $params = [
            'active' => $active,
        ];

        $response = $this->httpPut(sprintf('/v3/domains/%s/tracking/click', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, UpdateClickTrackingResponse::class);
    }

    /**
     * Updates a domain open tracking settings.
     * @param  string                                             $domain         The name of the domain
     * @param  string                                             $active         The status for this tracking (one of: yes, no)
     * @param  array                                              $requestHeaders
     * @return UpdateOpenTrackingResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function updateOpenTracking(string $domain, string $active, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($active);
        Assert::oneOf($active, ['yes', 'no']);

        $params = [
            'active' => $active,
        ];

        $response = $this->httpPut(sprintf('/v3/domains/%s/tracking/open', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, UpdateOpenTrackingResponse::class);
    }

    /**
     * Updates a domain unsubscribe tracking settings.
     * @param  string                                                    $domain         The name of the domain
     * @param  string                                                    $active         The status for this tracking (one of: yes, no)
     * @param  string                                                    $htmlFooter     The footer for HTML emails
     * @param  string                                                    $textFooter     The footer for plain text emails
     * @param  array                                                     $requestHeaders
     * @return UpdateUnsubscribeTrackingResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function updateUnsubscribeTracking(string $domain, string $active, string $htmlFooter, string $textFooter, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($active);
        Assert::oneOf($active, ['yes', 'no', 'true', 'false']);
        Assert::stringNotEmpty($htmlFooter);

        $params = [
            'active' => (in_array($active, ['yes', 'true'], true)) ? 'true' : 'false',
            'html_footer' => $htmlFooter,
            'text_footer' => $textFooter,
        ];

        $response = $this->httpPut(sprintf('/v3/domains/%s/tracking/unsubscribe', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, UpdateUnsubscribeTrackingResponse::class);
    }

    /**
     * Updates a CNAME used for tracking opens and clicks.
     *
     * @param string $domain    The name of the domain
     * @param string $webPrefix The tracking CNAME for a domain
     *
     * @return WebPrefixResponse|array|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function updateWebPrefix(string $domain, string $webPrefix)
    {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($webPrefix);

        $params = [
            'web_prefix' => $webPrefix,
        ];

        $response = $this->httpPut(sprintf('/v3/domains/%s/web_prefix', $domain), $params);

        return $this->hydrateResponse($response, WebPrefixResponse::class);
    }
}
