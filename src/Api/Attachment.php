<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Model\Attachment\Attachment as Model;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Attachment extends HttpApi
{
    /**
     * @return Model|ResponseInterface
     */
    public function show(string $url)
    {
        Assert::stringNotEmpty($url);
        Assert::regex($url, '@https://.*mailgun\.(net|org)/v.+@');
        Assert::regex($url, '|/attachments/[0-9]+|');

        $response = $this->httpGet($url);

        return $this->hydrateResponse($response, Model::class);
    }
}
