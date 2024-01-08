<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\EmailValidationV4;

use Mailgun\Model\EmailValidationV4\Summary as SummaryAlias;
use Mailgun\Model\EmailValidationV4\SummaryResult;
use Mailgun\Model\EmailValidationV4\SummaryRisk;
use Mailgun\Tests\Model\BaseModel;

class Summary extends BaseModel
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "result": {
        "deliverable": 181854,
        "do_not_send": 5647,
        "undeliverable": 12116,
        "catch_all" : 2345,
        "unknown": 5613
    },
    "risk": {
        "high": 17763,
        "low": 142547,
        "medium": 41652,
        "unknown": 5613
    }
}
JSON;
        $model = SummaryAlias::create(json_decode($json, true));
        $this->assertInstanceOf(SummaryResult::class, $model->getResult());
        $this->assertInstanceOf(SummaryRisk::class, $model->getRisk());
    }
}
