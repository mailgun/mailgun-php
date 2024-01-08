<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\DeleteCredentialResponse as DeleteCredentialResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class DeleteCredentialResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "message": "Credentials have been deleted",
  "spec": "alice@samples.mailgun.org"
}

JSON;
        $model = DeleteCredentialResponseAlias::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
        $this->assertEmpty($model->getError());
        $this->assertNotEmpty($model->getSpec());
    }
}
