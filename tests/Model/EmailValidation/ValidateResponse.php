<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\EmailValidation;

use Mailgun\Model\EmailValidation\Parts;
use Mailgun\Model\EmailValidation\ValidateResponse as ValidateResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class ValidateResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "address": "foo@mailgun.net",
    "did_you_mean": null,
    "is_disposable_address": false,
    "is_role_address": true,
    "is_valid": true,
    "mailbox_verification": "true",
    "parts": {
        "display_name": null,
        "domain": "mailgun.net",
        "local_part": "foo"
    }
}
JSON;
        $model = ValidateResponseAlias::create(json_decode($json, true));
        $this->assertTrue($model->isMailboxVerification());
    }

    public function testCreateWithoutMailboxVerification()
    {
        $json =
            <<<'JSON'
{
  "address": "foo@mailgun.net",
  "did_you_mean": null,
  "is_disposable_address": false,
  "is_role_address": false,
  "is_valid": true,
  "mailbox_verification": null,
  "parts": {
      "display_name": null,
      "domain": "mailgun.net",
      "local_part": "foo"
  },
  "reason": null
}
JSON;
        $model = ValidateResponseAlias::create(json_decode($json, true));
        $this->assertFalse($model->isMailboxVerification());
    }

    public function testEmailValidation()
    {
        $data = [
            'address' => 'foo@mailgun.net',
            'did_you_mean' => null,
            'is_disposable_address' => false,
            'is_role_address' => false,
            'is_valid' => true,
            'mailbox_verification' => null,
            'parts' => ['display_name' => null, 'domain' => 'mailgun.net', 'local_part' => 'foo'],
            'reason' => null,
        ];

        $parts = ValidateResponseAlias::create($data);

        $this->assertEquals($data['address'], $parts->getAddress());
        $this->assertEquals($data['did_you_mean'], $parts->getDidYouMean());
        $this->assertEquals($data['is_disposable_address'], $parts->isDisposableAddress());
        $this->assertEquals($data['is_role_address'], $parts->isRoleAddress());
        $this->assertEquals($data['is_valid'], $parts->isValid());
        $this->assertEquals($data['mailbox_verification'], $parts->isMailboxVerification());
        $this->assertInstanceOf(Parts::class, $parts->getParts());
        $this->assertEquals($data['reason'], $parts->getReason());
    }
}
