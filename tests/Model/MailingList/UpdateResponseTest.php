<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\MailingList;

use Mailgun\Model\Domain\ConnectionResponse;
use Mailgun\Model\MailingList\CreateResponse;
use Mailgun\Model\MailingList\MailingList;
use Mailgun\Tests\Model\BaseModelTest;

class UpdateResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "message": "Mailing list has been updated",
  "list": {
    "members_count": 7,
    "description": "My updated test mailing list",
    "created_at": "Wed, 06 Mar 2013 11:39:51 GMT",
    "access_level": "readonly",
    "address": "dev@samples.mailgun.org",
    "name": "Test List Updated"
  }
}
JSON;
        $model = CreateResponse::create(json_decode($json, true));
        $this->assertEquals('Mailing list has been updated', $model->getMessage());
        $this->assertInstanceOf(MailingList::class, $model->getList());
        $this->assertEquals(7, $model->getList()->getMembersCount());
    }
}
