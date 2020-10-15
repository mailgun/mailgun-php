<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Suppression\Whitelists;

use Mailgun\Model\Suppression\Whitelist\ShowResponse;
use Mailgun\Tests\Model\BaseModelTest;

class ShowResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "value": "alice@example.com",
    "reason": "why the record was created",
    "type": "address",
    "createdAt": "Fri, 21 Oct 2011 11:02:55 GMT"
}
JSON;

        $model = ShowResponse::create(json_decode($json, true));
        $this->assertEquals('alice@example.com', $model->getValue());
        $this->assertEquals('why the record was created', $model->getReason());
        $this->assertEquals('address', $model->getType());
        $this->assertEquals('2011-10-21 11:02:55', $model->getCreatedAt()->format('Y-m-d H:i:s'));
    }
}
