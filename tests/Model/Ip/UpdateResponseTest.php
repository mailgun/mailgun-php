<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Ip;

use Mailgun\Model\Ip\UpdateResponse;
use Mailgun\Tests\Model\BaseModelTest;

class UpdateResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "message": "success"
}
JSON;
        $model = UpdateResponse::create(json_decode($json, true));
        $this->assertEquals('success', $model->getMessage());
    }
}
