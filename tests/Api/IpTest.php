<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\Ip;
use Mailgun\Model\Ip\UpdateResponse;

class IpTest extends TestCase
{
    protected function getApiClass()
    {
        return Ip::class;
    }

    public function testAssign()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/domains/example.com/ips');
        $this->setRequestBody(
            [
            'ip' => '127.0.0.1',
            ]
        );
        $this->setHydrateClass(UpdateResponse::class);

        $api = $this->getApiInstance();
        $api->assign('example.com', '127.0.0.1');
    }
}
