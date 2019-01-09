<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Tag;

use Mailgun\Model\Tag\IndexResponse;
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
        "tag": "red",
        "description": "red signup button"
      },
      {
        "tag": "green",
        "description": "green signup button"
      }
  ],
  "paging": {
    "next":
        "https://url_to_next_page",
    "previous":
        "https://url_to_previous_page",
    "first":
        "https://url_to_first_page",
    "last":
        "https://url_to_last_page"
  }
}
JSON;
        $model = IndexResponse::create(json_decode($json, true));

        $tags = $model->getItems();
        $this->assertCount(2, $tags);
        $tag = $tags[0];
        $this->assertEquals('red', $tag->getTag());
    }
}
