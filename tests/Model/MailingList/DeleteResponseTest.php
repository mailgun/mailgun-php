<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\MailingList;

use Mailgun\Model\MailingList\DeleteResponse;
use Mailgun\Tests\Model\BaseModelTest;

class DeleteResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "message": "Mailing list has been deleted",
  "address": "dev@samples.mailgun.org"
}
JSON;
        $model = DeleteResponse::create(json_decode($json, true));
        $this->assertEquals('Mailing list has been deleted', $model->getMessage());
        $this->assertEquals('dev@samples.mailgun.org', $model->getAddress());
    }
}
