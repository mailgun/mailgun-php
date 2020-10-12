<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\MailingList;

use Mailgun\Model\MailingList\ValidationStatusSummaryResult;
use Mailgun\Tests\Model\BaseModelTest;

class ValidationStatusSummaryResultTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "deliverable": 184199,
    "do_not_send": 5647,
    "undeliverable": 12116,
    "unknown": 5613
}
JSON;
        $model = ValidationStatusSummaryResult::create(json_decode($json, true));
        $this->assertEquals(184199, $model->getDeliverable());
        $this->assertEquals(5647, $model->getDoNotSend());
        $this->assertEquals(12116, $model->getUndeliverable());
        $this->assertEquals(5613, $model->getUnknown());
    }
}
