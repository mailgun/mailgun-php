<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\IndexResponse as IndexResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class IndexResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "total_count": 1,
  "items": [
    {
      "created_at": "Wed, 10 Jul 2013 19:26:52 GMT",
      "smtp_login": "postmaster@samples.mailgun.org",
      "name": "samples.mailgun.org",
      "smtp_password": "4rtqo4p6rrx9",
      "wildcard": true,
      "spam_action": "disabled",
      "state": "active"
    }
  ]
}

JSON;
        $model = IndexResponseAlias::create(json_decode($json, true));
        $this->assertEquals(1, $model->getTotalCount());
        $this->assertCount(1, $model->getDomains());
    }
}
