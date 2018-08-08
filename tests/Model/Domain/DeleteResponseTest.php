<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\DeleteResponse;
use Mailgun\Tests\Model\BaseModelTest;

class DeleteResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "message": "Domain has been deleted"
}
JSON;
        $model = DeleteResponse::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
    }
}
