<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\ClickTracking;
use Mailgun\Model\Domain\WebPrefixResponse;
use Mailgun\Tests\Model\BaseModelTest;

class WebPrefixResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
  "message": "Domain web prefix updated"
}
JSON;
        $model = WebPrefixResponse::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
    }
}
