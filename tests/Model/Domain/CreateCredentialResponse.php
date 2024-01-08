<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\CreateCredentialResponse as CreateCredentialResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class CreateCredentialResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "message": "Created 1 credentials pair(s)"
}
JSON;
        $model = CreateCredentialResponseAlias::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
    }
}
