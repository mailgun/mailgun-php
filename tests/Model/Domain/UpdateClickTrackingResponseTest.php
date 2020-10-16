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
use Mailgun\Model\Domain\UpdateClickTrackingResponse;
use Mailgun\Tests\Model\BaseModelTest;

class UpdateClickTrackingResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
  "click": {
    "active": "htmlonly"
  },
  "message": "Domain tracking settings have been updated"
}
JSON;
        $model = UpdateClickTrackingResponse::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
        $this->assertEquals('Domain tracking settings have been updated', $model->getMessage());
        $this->assertNotEmpty($model->getClick());
        $this->assertInstanceOf(ClickTracking::class, $model->getClick());
        $this->assertEquals('htmlonly', $model->getClick()->getActive());
        $this->assertFalse($model->getClick()->isActive());
    }
}
