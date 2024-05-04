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
     *
     * @see    https://documentation.mailgun.com/en/latest/api-domains.html#domains
     * @param  string                                 $domain             name of the domain
     * @param  string|null                            $smtpPass           password for SMTP authentication
     * @param  string|null                            $spamAction         `disable` or `tag` - inbound spam filtering
     * @param  bool                                   $wildcard           domain will accept email for subdomains
     * @param  bool                                   $forceDkimAuthority force DKIM authority
     * @param  string[]                               $ips                an array of ips to be assigned to the domain
     * @param  ?string                                $pool_id            pool id to assign to the domain
     * @param  string                                 $webScheme          `http` or `https` - set your open, click and unsubscribe URLs to use http or https. The default is http
     * @param  string                                 $dkimKeySize        Set length of your domain’s generated DKIM
     *                                                                    key
     * @return CreateResponse|array|ResponseInterface
     * @throws Exception
     */
    public function create(
        string $domain,
        string $smtpPass = null,
        string $spamAction = null,
        bool $wildcard = null,
        bool $forceDkimAuthority = null,
        ?array $ips = null,
        ?string $pool_id = null,
        string $webScheme = 'http',
        string $dkimKeySize = '1024',
        array $requestHeaders = []
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

        $response = $this->httpPost('/v4/domains', $params, $requestHeaders);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * Removes a domain from the account.
     * WARNING: This action is irreversible! Be cautious!
     * @param  string                                 $domain         name of the domain
     * @param  array                                  $requestHeaders
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
}
