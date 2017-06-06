<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Mock;

use Mailgun\Tests\Mock\Connection\PostBinBroker;

class PostBinMailgun extends Mailgun
{
    public function __construct($apiKey = null, $apiEndpoint = 'api.mailgun.net', $apiVersion = 'v3')
    {
        $this->restClient = new PostBinBroker($apiKey, $apiEndpoint, $apiVersion);
    }
}
