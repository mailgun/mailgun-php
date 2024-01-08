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
use Mailgun\Model\Domain\UpdateOpenTrackingResponse as UpdateOpenTrackingResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class UpdateOpenTrackingResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
  "open": {
    "active": false
  },
  "message": "Domain tracking settings have been updated"
}
JSON;
        $model = UpdateOpenTrackingResponseAlias::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
        $this->assertEquals('Domain tracking settings have been updated', $model->getMessage());
        $this->assertNotEmpty($model->getOpen());
        $this->assertInstanceOf(OpenTracking::class, $model->getOpen());
        $this->assertFalse($model->getOpen()->isActive());
    }
}
