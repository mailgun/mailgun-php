<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Tag;

use Mailgun\Model\Tag\ProviderResponse as ProviderResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class ProviderResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "providers": {
      "gmail.com": {
          "accepted": 23,
          "clicked": 15,
          "complained": 0,
          "delivered": 23,
          "opened": 19,
          "unique_clicked": 2,
          "unique_opened": 7,
          "unsubscribed": 1
      },
      "yahoo.com": {
          "accepted": 16,
          "clicked": 8,
          "complained": 2,
          "delivered": 8,
          "opened": 4,
          "unique_clicked": 0,
          "unique_opened": 0,
          "unsubscribed": 0
      }
  },
  "tag": "exampletag"
}
JSON;
        $model = ProviderResponseAlias::create(json_decode($json, true));

        $this->assertCount(2, $model->getProviders());
        $this->assertEquals('exampletag', $model->getTag());
    }
}
