<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\EmailValidation;

use Mailgun\Model\EmailValidation\ValidateResponse;
use Mailgun\Tests\Model\BaseModelTest;

class ValidateResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "address": "foo@mailgun.net",
    "is_disposable_address": false,
    "is_role_address": true,
    "reason": [],
    "result": "deliverable",
    "risk": "low"
}
JSON;
        $model = ValidateResponse::create(json_decode($json, true));
        $this->assertTrue($model->isMailboxVerification());
    }

    public function testEmailValidation()
    {
        $data = [
            'address' => 'foo@mailgun.net',
            'is_disposable_address' => false,
            'is_role_address' => false,
            'reason' => [],
            'result' => 'deliverable',
            'risk' => 'low',
        ];

        $parts = ValidateResponse::create($data);

        $this->assertEquals($data['address'], $parts->getAddress());
        $this->assertEquals($data['is_disposable_address'], $parts->isDisposableAddress());
        $this->assertEquals($data['is_role_address'], $parts->isRoleAddress());
        $this->assertEquals($data['reason'], $parts->getReason());
        $this->assertEquals($data['result'], $parts->getResult());
        $this->assertEquals($data['risk'], $parts->getRisk());
    }
}
