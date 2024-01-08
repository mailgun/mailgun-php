<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Tag;

use Mailgun\Model\Tag\DeviceResponse as DeviceResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class DeviceResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "devices": {
      "desktop": {
          "clicked": 8,
          "complained": 1,
          "opened": 8,
          "unique_clicked": 0,
          "unique_opened": 0,
          "unsubscribed": 0
      },
      "mobile": {
          "clicked": 3,
          "complained": 1,
          "opened": 5,
          "unique_clicked": 0,
          "unique_opened": 0,
          "unsubscribed": 0
      }
  },
  "tag": "exampletag"
}
JSON;
        $model = DeviceResponseAlias::create(json_decode($json, true));

        $this->assertCount(2, $model->getDevices());
        $this->assertEquals('exampletag', $model->getTag());
    }
}
