<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\Domain;
use Mailgun\Model\Domain\VerifyResponse;
use Mailgun\Tests\Model\BaseModelTest;

class VerifyResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "domain": {
    "created_at": "Wed, 10 Jul 2013 19:26:52 GMT",
    "smtp_login": "postmaster@domain.com",
    "name": "domain.com",
    "smtp_password": "4rtqo4p6rrx9",
    "wildcard": false,
    "spam_action": "tag",
    "state": "active"
  },
  "receiving_dns_records": [
    {
      "priority": "10",
      "record_type": "MX",
      "valid": "valid",
      "value": "mxa.mailgun.org"
    },
    {
      "priority": "10",
      "record_type": "MX",
      "valid": "valid",
      "value": "mxb.mailgun.org"
    }
  ],
  "sending_dns_records": [
    {
      "record_type": "TXT",
      "valid": "valid",
      "name": "domain.com",
      "value": "v=spf1 include:mailgun.org ~all"
    },
    {
      "record_type": "TXT",
      "valid": "valid",
      "name": "domain.com",
      "value": "k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUA...."
    },
    {
      "record_type": "CNAME",
      "valid": "valid",
      "name": "email.domain.com",
      "value": "mailgun.org"
    }
  ]
}
JSON;
        $model = VerifyResponse::create(json_decode($json, true));
        $this->assertNull($model->getMessage());
    }
}
