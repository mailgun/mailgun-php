<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\ConnectionResponse as ConnectionResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class ConnectionResponse extends BaseModel
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
        $model = ConnectionResponseAlias::create(json_decode($json, true));
        $this->assertFalse($model->getRequireTLS());
        $this->assertFalse($model->getSkipVerification());
    }
}
