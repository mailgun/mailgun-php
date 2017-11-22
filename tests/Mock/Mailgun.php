<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Mock;

use Mailgun\Mailgun as Base;
use Mailgun\Tests\Mock\Connection\TestBroker;

class Mailgun extends Base
{
    protected $debug;
    protected $restClient;

    public function __construct($apiKey = null, $apiEndpoint = 'api.mailgun.net', $apiVersion = 'v3')
    {
        $this->restClient = new TestBroker($apiKey, $apiEndpoint, $apiVersion);
    }
}
