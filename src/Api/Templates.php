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
use Mailgun\Model\Domain\CreateResponse;
use Mailgun\Model\Domain\IndexResponse;
use Mailgun\Model\Templates\GetResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://documentation.mailgun.com/docs/mailgun/api-reference/openapi-final/tag/Templates/#tag/Templates/operation/httpapi.(*TemplateAPIControler).GetPage-fm-9
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class Templates extends HttpApi
{
    private const PAGE_NEXT = 'next';
    private const PAGE_FIRST = 'first';
    private const PAGE_PREVIOUS = 'previous';
    private const PAGE_LAST = 'last';

    /**
     * @param string $domain
     * @param int $limit
     * @param string $page
     * @param string $pivot
     * @param array $requestHeaders
     * @return mixed|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function get(string $domain, int $limit, string $page, string $pivot, array $requestHeaders = [])
    {
        Assert::inArray($page, [self::PAGE_LAST, self::PAGE_FIRST, self::PAGE_PREVIOUS, self::PAGE_NEXT]);

        $params = [
            'limit' => $limit,
            'skip' => $page,
            'p' => $pivot
        ];

        $response = $this->httpGet(sprintf('/v3/%s/templates', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, GetResponse::class);
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

        $params['name'] = $domain;



        $response = $this->httpPost('/v3/domains', $params, $requestHeaders);

        return $this->hydrateResponse($response, CreateResponse::class);
    }
}
