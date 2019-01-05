<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\MailingList\Member;

use Mailgun\Model\Domain\ConnectionResponse;
use Mailgun\Model\MailingList\Member\CreateResponse;
use Mailgun\Model\MailingList\MailingList;
use Mailgun\Model\MailingList\Member\Member;
use Mailgun\Model\MailingList\Member\UpdateResponse;
use Mailgun\Tests\Model\BaseModelTest;

class UpdateResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "member": {
      "vars": {
          "age": 26
      },
      "name": "Foo Bar",
      "subscribed": false,
      "address": "bar@example.com"
  },
  "message": "Mailing list member has been updated"
}
JSON;
        $model = UpdateResponse::create(json_decode($json, true));
        $this->assertEquals('Mailing list member has been updated', $model->getMessage());
        $this->assertInstanceOf(Member::class, $model->getMember());
        $this->assertEquals('Foo Bar', $model->getMember()->getName());
    }
}
