<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\MailingList;

use Mailgun\Model\MailingList\ValidationStatusSummaryRisk as ValidationStatusSummaryRiskAlias;
use Mailgun\Tests\Model\BaseModel;

class ValidationStatusSummaryRisk extends BaseModel
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "high": 17763,
    "low": 142547,
    "medium": 41652,
    "unknown": 5614
}
JSON;
        $model = ValidationStatusSummaryRiskAlias::create(json_decode($json, true));
        $this->assertEquals(17763, $model->getHigh());
        $this->assertEquals(142547, $model->getLow());
        $this->assertEquals(41652, $model->getMedium());
        $this->assertEquals(5614, $model->getUnknown());
    }
}
