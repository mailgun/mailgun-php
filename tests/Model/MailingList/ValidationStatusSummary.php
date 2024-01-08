<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\MailingList;

use Mailgun\Model\MailingList\ValidationStatusSummary as ValidationStatusSummaryAlias;
use Mailgun\Model\MailingList\ValidationStatusSummaryResult;
use Mailgun\Model\MailingList\ValidationStatusSummaryRisk;
use Mailgun\Tests\Model\BaseModel;

class ValidationStatusSummary extends BaseModel
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "result": {
        "deliverable": 184199,
        "do_not_send": 5647,
        "undeliverable": 12116,
        "unknown": 5613
    },
    "risk": {
        "high": 17763,
        "low": 142547,
        "medium": 41652,
        "unknown": 5614
    }
}
JSON;
        $model = ValidationStatusSummaryAlias::create(json_decode($json, true));
        $this->assertInstanceOf(ValidationStatusSummaryResult::class, $model->getResult());
        $this->assertEquals(184199, $model->getResult()->getDeliverable());
        $this->assertEquals(5647, $model->getResult()->getDoNotSend());
        $this->assertEquals(12116, $model->getResult()->getUndeliverable());
        $this->assertEquals(5613, $model->getResult()->getUnknown());

        $this->assertInstanceOf(ValidationStatusSummaryRisk::class, $model->getRisk());
        $this->assertEquals(17763, $model->getRisk()->getHigh());
        $this->assertEquals(142547, $model->getRisk()->getLow());
        $this->assertEquals(41652, $model->getRisk()->getMedium());
        $this->assertEquals(5614, $model->getRisk()->getUnknown());
    }
}
