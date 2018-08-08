<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\ConnectionResponse;
use Mailgun\Tests\Model\BaseModelTest;

class ConnectionResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "connection": {
    "require_tls": false,
    "skip_verification": false
  }
}
JSON;
        $model = ConnectionResponse::create(json_decode($json, true));
        $this->assertFalse($model->getRequireTLS());
        $this->assertFalse($model->getSkipVerification());
    }
}
