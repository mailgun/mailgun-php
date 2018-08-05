<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Model\AttachmentResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Attachment extends HttpApi
{
    /**
     * @param string $url
     *
     * @return AttachmentResponse
     */
    public function show($url)
    {
        Assert::stringNotEmpty($url);
        Assert::regex('|https://.*mailgun\.net/v.+|', $url);
        Assert::regex('|/attachments/[0-9]+|', $url);

        $response = $this->httpGet($url);

        return $this->hydrateResponse($response, AttachmentResponse::class);
    }
}
