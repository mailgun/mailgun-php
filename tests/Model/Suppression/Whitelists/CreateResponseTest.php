<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Suppression\Whitelists;

use Mailgun\Model\Suppression\Whitelist\CreateResponse;
use Mailgun\Tests\Model\BaseModelTest;

class CreateResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "message":"Address/Domain has been added to the whitelists table",
    "type":"domain",
    "value":"example.com"
}
JSON;

        $model = CreateResponse::create(json_decode($json, true));
        $this->assertEquals('Address/Domain has been added to the whitelists table', $model->getMessage());
        $this->assertEquals('domain', $model->getType());
        $this->assertEquals('example.com', $model->getValue());
    }
}
