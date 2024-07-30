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
use Mailgun\Model\Templates\CreateResponse;
use Mailgun\Model\Templates\IndexResponse;
use Mailgun\Model\Templates\ShowResponse;
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
     * @param  string                          $domain
     * @param  int                             $limit
     * @param  string                          $page
     * @param  string                          $pivot
     * @param  array                           $requestHeaders
     * @return IndexResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function index(string $domain, int $limit, string $page, string $pivot, array $requestHeaders = [])
    {
        Assert::inArray($page, [self::PAGE_LAST, self::PAGE_FIRST, self::PAGE_PREVIOUS, self::PAGE_NEXT]);

        $params = [
            'limit' => $limit,
            'page' => $page,
            'p' => $pivot,
        ];

        $response = $this->httpGet(sprintf('/v3/%s/templates', $domain), $params, $requestHeaders);

        return $this->hydrateResponse($response, IndexResponse::class);
    }

    /**
     * @param  string                   $domain
     * @param  string                   $templateId
     * @param  array                    $requestHeaders
     * @return mixed|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function show(string $domain, string $templateId, array $requestHeaders = [])
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($templateId);

        $response = $this->httpGet(sprintf('/v3/%s/templates/%s', $domain, $templateId), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }

    /**
     * @param  string                           $domain
     * @param  string                           $name
     * @param  string|null                      $template
     * @param  array|null                       $headers
     * @param  string|null                      $tag
     * @param  string|null                      $comment
     * @param  string|null                      $createdBy
     * @param  string|null                      $description
     * @param  string|null                      $engine
     * @param  array                            $requestHeaders
     * @return CreateResponse|ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function create(
        string $domain,
        string $name,
        ?string $template = null,
        ?array $headers = null,
        ?string $tag = null,
        ?string $comment = null,
        ?string $createdBy = null,
        ?string $description = null,
        ?string $engine = null,
        array $requestHeaders = []
    ) {
        Assert::stringNotEmpty($domain);
        Assert::stringNotEmpty($name);

        $body = [
            'name' => $name,
        ];

        if (!empty($template)) {
            $body['template'] = $template;
        }
        if (!empty($tag)) {
            $body['tag'] = $tag;
        }
        if (!empty($comment)) {
            $body['comment'] = $comment;
        }
        if (!empty($createdBy)) {
            $body['createdBy'] = $createdBy;
        }
        if (!empty($headers)) {
            $body['headers'] = json_encode($headers);
        }
        if (!empty($description)) {
            $body['description'] = $description;
        }
        if (!empty($engine)) {
            $body['engine'] = $engine;
        }

        $response = $this->httpPost(sprintf('/v3/%s/templates', $domain), $body, $requestHeaders);

        return $this->hydrateResponse($response, CreateResponse::class);
    }

    /**
     * @param  string                   $domain
     * @param  string                   $templateName
     * @param  array                    $requestHeaders
     * @return mixed|ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function deleteTemplate(string $domain, string $templateName, array $requestHeaders = [])
    {
        $response = $this->httpDelete(sprintf('/v3/%s/templates/%s', $domain, $templateName), [], $requestHeaders);

        return $this->hydrateResponse($response, ShowResponse::class);
    }
}
