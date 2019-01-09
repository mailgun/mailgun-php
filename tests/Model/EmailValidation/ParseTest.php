<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\EmailValidation;

use Mailgun\Model\EmailValidation\Parse;
use Mailgun\Tests\Model\BaseModelTest;

class ParseTest extends BaseModelTest
{
    public function testParseConstructorWithValidData()
    {
        $this->markTestIncomplete('WIP');

        $data = [
            'parsed' => ['parsed data'],
            'unparseable' => ['unparseable data'],
        ];

        $parts = Parse::create($data);

        $this->assertEquals($data['parsed'], $parts->getParsed());
        $this->assertEquals($data['unparseable'], $parts->getUnparseable());
    }

    public function testParseConstructorWithInvalidData()
    {
        $this->markTestIncomplete('WIP');

        $data = [
            'parsed' => null,
            'unparseable' => null,
        ];

        $parts = Parse::create($data);

        $this->assertEquals([], $parts->getParsed());
        $this->assertEquals([], $parts->getUnparseable());
    }
}
