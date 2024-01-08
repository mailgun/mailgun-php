<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\UpdateConnectionResponse as UpdateConnectionResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class UpdateConnectionResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "message": "Domain connection settings have been updated, may take 10 minutes to fully propagate",
  "require-tls": true,
  "skip-verification": false
}
JSON;
        $model = UpdateConnectionResponseAlias::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
        $this->assertTrue($model->getRequireTLS());
        $this->assertFalse($model->getSkipVerification());
    }
}
