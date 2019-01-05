<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\MailingList\Member;

use Mailgun\Model\MailingList\Member\IndexResponse;
use Mailgun\Model\MailingList\Member\Member;
use Mailgun\Tests\Model\BaseModelTest;

class IndexResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "items": [
      {
          "vars": {
              "age": 26
          },
          "name": "Foo Bar",
          "subscribed": false,
          "address": "bar@example.com"
      }
  ],
  "paging": {
    "first": "https://url_to_first_page",
    "last": "https://url_to_last_page",
    "next": "http://url_to_next_page",
    "previous": "http://url_to_previous_page"
  }
}

JSON;
        $model = IndexResponse::create(json_decode($json, true));
        $members = $model->getItems();
        $this->assertCount(1, $members);
        $member = $members[0];
        $this->assertInstanceOf(Member::class, $member);
        $this->assertEquals('Foo Bar', $member->getName());
    }
}
