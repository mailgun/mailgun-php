<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\CredentialResponse;
use Mailgun\Tests\Model\BaseModelTest;

class CredentialResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "total_count": 2,
  "items": [
    {
      "size_bytes": 5,
      "created_at": "Tue, 27 Sep 2011 20:24:22 GMT",
      "mailbox": "user@samples.mailgun.org",
      "login": "user"
    },
    {
      "size_bytes": 0,
      "created_at": "Thu, 06 Oct 2011 10:22:36 GMT",
      "mailbox": "user@samples.mailgun.org",
      "login": "user@samples.mailgun.org"
    }
  ]
}
JSON;
        $model = CredentialResponse::create(json_decode($json, true));
        $this->assertEquals(2, $model->getTotalCount());
        $this->assertCount(2, $model->getCredentials());
    }
}
