<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\UpdateCredentialResponse as UpdateCredentialResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class UpdateCredentialResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "message": "Password changed"
}
JSON;
        $model = UpdateCredentialResponseAlias::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
    }
}
