<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Suppression\Whitelists;

use Mailgun\Model\Suppression\Whitelist\DeleteResponse;
use Mailgun\Tests\Model\BaseModelTest;

class DeleteResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "message":"Whitelist address/domain has been removed",
    "value":"alice@example.com"
}
JSON;

        $model = DeleteResponse::create(json_decode($json, true));
        $this->assertEquals('Whitelist address/domain has been removed', $model->getMessage());
        $this->assertEquals('alice@example.com', $model->getValue());
    }
}
