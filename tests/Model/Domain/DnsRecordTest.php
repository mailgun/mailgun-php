<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\DnsRecord;
use Mailgun\Tests\Model\BaseModelTest;

class DnsRecordTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "record_type": "TXT",
  "valid": "valid",
  "name": "example.com",
  "value": "v=spf1 include:mailgun.org ~all"
}
JSON;
        $model = DnsRecord::create(json_decode($json, true));
        $this->assertNotEmpty($model->getType());
        $this->assertNotEmpty($model->getValidity());
        $this->assertTrue($model->isValid());
        $this->assertNotEmpty($model->getName());
        $this->assertNotEmpty($model->getValue());
    }
}
