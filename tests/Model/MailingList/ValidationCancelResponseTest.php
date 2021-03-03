<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\MailingList;

use Mailgun\Model\MailingList\ValidationCancelResponse;
use Mailgun\Tests\Model\BaseModelTest;

class ValidationCancelResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "message": "Validation job canceled."
}
JSON;
        $model = ValidationCancelResponse::create(json_decode($json, true));
        $this->assertEquals('Validation job canceled.', $model->getMessage());
    }
}
