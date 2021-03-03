<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Event;

use Mailgun\Model\EmailValidationV4\SummaryResult;
use Mailgun\Tests\Model\BaseModelTest;

class SummaryResultTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "deliverable": 181854,
    "do_not_send": 5647,
    "undeliverable": 12116,
    "catch_all": 2345,
    "unknown": 5613
}
JSON;
        $model = SummaryResult::create(json_decode($json, true));
        $this->assertEquals(181854, $model->getDeliverable());
        $this->assertEquals(5647, $model->getDoNotSend());
        $this->assertEquals(12116, $model->getUndeliverable());
        $this->assertEquals(2345, $model->getCatchAll());
        $this->assertEquals(5613, $model->getUnknown());
    }
}
