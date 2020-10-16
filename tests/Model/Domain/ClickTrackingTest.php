<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\ClickTracking;
use Mailgun\Tests\Model\BaseModelTest;

class ClickTrackingTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "active": true
}
JSON;
        $model = ClickTracking::create(json_decode($json, true));
        $this->assertTrue($model->isActive());
    }
}
