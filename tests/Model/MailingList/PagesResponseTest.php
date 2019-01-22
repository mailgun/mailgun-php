<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\MailingList;

use Mailgun\Model\MailingList\PagesResponse;
use Mailgun\Tests\Model\BaseModelTest;

class PagesResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "items": [
    {
      "access_level": "everyone",
      "address": "dev@samples.mailgun.org",
      "created_at": "Tue, 06 Mar 2012 05:44:45 GMT",
      "description": "Mailgun developers list",
      "members_count": 1,
      "name": ""
    },
    {
      "access_level": "readonly",
      "address": "bar@example.com",
      "created_at": "Wed, 06 Mar 2013 11:39:51 GMT",
      "description": "",
      "members_count": 2,
      "name": ""
    }
  ],
  "paging": {
    "first": "https://url_to_next_page",
    "last": "https://url_to_last_page",
    "next": "https://url_to_next_page",
    "previous": "https://url_to_previous_page"
  }
}
JSON;
        $model = PagesResponse::create(json_decode($json, true));
        $lists = $model->getLists();
        $this->assertCount(2, $lists);
        $list = $lists[0];

        $this->assertEquals('everyone', $list->getAccessLevel());
    }
}
