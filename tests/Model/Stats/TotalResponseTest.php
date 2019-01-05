<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Stats;

use Mailgun\Model\Stats\TotalResponse;
use Mailgun\Tests\Model\BaseModelTest;

class TotalResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "end": "Fri, 01 Apr 2012 00:00:00 UTC",
  "resolution": "month",
  "start": "Tue, 14 Feb 2012 00:00:00 UTC",
  "stats": [
    {
      "time": "Tue, 14 Feb 2012 00:00:00 UTC",
      "accepted": {
        "outgoing": 10,
        "incoming": 5,
        "total": 15
      },
      "delivered": {
          "smtp": 15,
          "http": 5,
          "total": 20
      },
      "failed": {
        "permanent": {
          "bounce": 4,
          "delayed-bounce": 1,
          "suppress-bounce": 1,
          "suppress-unsubscribe": 2,
          "suppress-complaint": 3,
          "total": 10
        },
        "temporary": {
          "espblock": 1
        }
      },
      "complained": {
        "total": 1
      }
    }
  ]
}
JSON;
        $model = TotalResponse::create(json_decode($json, true));
        $this->assertEquals('2012-02-14', $model->getStart()->format('Y-m-d'));
        $this->assertCount(1, $model->getStats());
        $stats = $model->getStats();
        $stats = $stats[0];
        $this->assertEquals('2012-02-14', $stats->getTime()->format('Y-m-d'));
        $this->assertEquals('1', $stats->getComplained()['total']);
    }
}
