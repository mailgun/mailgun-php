<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain\Tracking;

use Mailgun\Model\Domain\Tracking\UpdateClickTrackingResponse;
use Mailgun\Tests\Model\BaseModelTest;

class UpdateClickTrackingResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "click": {
    "active": true
  },
  "message": "Domain tracking settings have been updated"
}
JSON;
        $model = UpdateClickTrackingResponse::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());

        $click = $model->getClick();
        $this->assertTrue($click->isActive());
        $this->assertFalse($click->isHtmlOnly());
    }
}
