<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Event;

use Mailgun\Model\EmailValidationV4\SummaryRisk;
use Mailgun\Tests\Model\BaseModelTest;

class SummaryRiskTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "high": 17763,
    "low": 142547,
    "medium": 41652,
    "unknown": 5613
}
JSON;
        $model = SummaryRisk::create(json_decode($json, true));
        $this->assertEquals(17763, $model->getHigh());
        $this->assertEquals(142547, $model->getLow());
        $this->assertEquals(41652, $model->getMedium());
        $this->assertEquals(5613, $model->getUnknown());
    }
}
