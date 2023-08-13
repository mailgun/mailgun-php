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
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Attachment extends HttpApi
{
    /**
     * @param  string                   $url
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function show(string $url): ResponseInterface
    {
        Assert::stringNotEmpty($url);
        Assert::regex($url, '@https://.*mailgun\.(net|org)/v.+@');
        Assert::regex($url, '|/attachments/[0-9]+|');

        $response = $this->httpGet($url);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $response;
    }
}
