<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\EmailValidation;

use Mailgun\Model\EmailValidation\ParseResponse;
use Mailgun\Tests\Model\BaseModelTest;

class ParseResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "parsed": [
        "Alice <alice@example.com>",
        "bob@example.com"
    ],
    "unparseable": [
    ]
}
JSON;
        $model = ParseResponse::create(json_decode($json, true));
        $this->assertNotEmpty($model->getParsed());
        $this->assertCount(2, $model->getParsed());
        $this->assertEmpty($model->getUnparseable());
    }

    public function testParseConstructorWithValidData()
    {
        $data = [
            'parsed' => ['parsed data'],
            'unparseable' => ['unparseable data'],
        ];

        $parts = ParseResponse::create($data);

        $this->assertEquals($data['parsed'], $parts->getParsed());
        $this->assertEquals($data['unparseable'], $parts->getUnparseable());
    }

    public function testParseConstructorWithInvalidData()
    {
        $data = [
            'parsed' => null,
            'unparseable' => null,
        ];

        $parts = ParseResponse::create($data);

        $this->assertEquals([], $parts->getParsed());
        $this->assertEquals([], $parts->getUnparseable());
    }
}
