<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Ip;

use Mailgun\Model\Ip\IndexResponse;
use Mailgun\Tests\Model\BaseModelTest;

class IndexResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "items": ["192.161.0.1", "192.168.0.2"],
  "total_count": 2
}
JSON;
        $model = IndexResponse::create(json_decode($json, true));
        $this->assertEquals(2, $model->getTotalCount());
        $items = $model->getItems();
        $this->assertCount(2, $items);
        $this->assertEquals('192.161.0.1', $items[0]);
    }
}
