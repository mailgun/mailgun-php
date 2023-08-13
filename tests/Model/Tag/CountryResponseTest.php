<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Tag;

use Mailgun\Model\Tag\CountryResponse;
use Mailgun\Tests\Model\BaseModelTest;

class CountryResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "countries": {
      "ad": {
          "clicked": 7,
          "complained": 4,
          "opened": 18,
          "unique_clicked": 0,
          "unique_opened": 2,
          "unsubscribed": 0
      },
      "ck": {
          "clicked": 13,
          "complained": 2,
          "opened": 1,
          "unique_clicked": 1,
          "unique_opened": 0,
          "unsubscribed": 2
      }
  },
  "tag": "exampletag"
}
JSON;
        $model = CountryResponse::create(json_decode($json, true));

        $this->assertCount(2, $model->getCountries());
        $this->assertEquals('exampletag', $model->getTag());
    }
}
