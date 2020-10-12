<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\OpenTracking;
use Mailgun\Model\Domain\UpdateOpenTrackingResponse;
use Mailgun\Tests\Model\BaseModelTest;

class UpdateOpenTrackingResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
  "open": {
    "active": "no"
  },
  "message": "Domain tracking settings have been updated"
}
JSON;
        $model = UpdateOpenTrackingResponse::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
        $this->assertEquals('Domain tracking settings have been updated', $model->getMessage());
        $this->assertNotEmpty($model->getOpen());
        $this->assertInstanceOf(OpenTracking::class, $model->getOpen());
        $this->assertFalse($model->getOpen()->isActive());
    }
}
