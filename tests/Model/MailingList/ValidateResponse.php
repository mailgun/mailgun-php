<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\MailingList;

use Mailgun\Model\MailingList\ValidateResponse as ValidateResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class ValidateResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
    "id": "listname@yourdomain.com",
    "message": "The validation job was submitted."
}
JSON;
        $model = ValidateResponseAlias::create(json_decode($json, true));
        $this->assertEquals('The validation job was submitted.', $model->getMessage());
        $this->assertEquals('listname@yourdomain.com', $model->getId());
    }
}
