<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\UpdateCredentialResponse;
use Mailgun\Tests\Model\BaseModelTest;

class UpdateCredentialResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "message": "Password changed"
}
JSON;
        $model = UpdateCredentialResponse::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
    }
}
