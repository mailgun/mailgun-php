<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Suppression\Whitelists;

use Mailgun\Model\Suppression\Whitelist\IndexResponse as IndexResponseAlias;
use Mailgun\Model\Suppression\Whitelist\Whitelist;
use Mailgun\Tests\Model\BaseModel;

class IndexResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "items": [
        {
            "value": "alice@example.com",
            "reason": "reason of white listing",
            "type": "address",
            "createdAt": "Fri, 21 Oct 2011 11:02:55 UTC"
        },
        {
            "value": "test.com",
            "reason": "reason of white listing",
            "type": "domain",
            "createdAt": "Fri, 21 Oct 2012 11:02:56 UTC"
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
        $model = IndexResponseAlias::create(json_decode($json, true));

        $this->assertEquals(2, $model->getTotalCount());
        $this->assertEquals('https://url_to_next_page', $model->getFirstUrl());
        $this->assertEquals('https://url_to_last_page', $model->getLastUrl());
        $this->assertEquals('https://url_to_next_page', $model->getNextUrl());
        $this->assertEquals('https://url_to_previous_page', $model->getPreviousUrl());

        $items = $model->getItems();
        $this->assertCount(2, $items);

        $item = $items[0];
        $this->assertInstanceOf(Whitelist::class, $item);
        $this->assertEquals('alice@example.com', $item->getValue());
        $this->assertEquals('reason of white listing', $item->getReason());
        $this->assertEquals('address', $item->getType());
        $this->assertEquals('2011-10-21 11:02:55', $item->getCreatedAt()->format('Y-m-d H:i:s'));

        $item = $items[1];
        $this->assertInstanceOf(Whitelist::class, $item);
        $this->assertEquals('test.com', $item->getValue());
        $this->assertEquals('reason of white listing', $item->getReason());
        $this->assertEquals('domain', $item->getType());
        $this->assertEquals('2012-10-26 11:02:56', $item->getCreatedAt()->format('Y-m-d H:i:s'));
    }
}
