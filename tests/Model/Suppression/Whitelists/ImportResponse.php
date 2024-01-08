<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Suppression\Whitelists;

use Mailgun\Model\Suppression\Whitelist\ImportResponse as ImportResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class ImportResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "message": "file uploaded successfully"
}
JSON;

        $model = ImportResponseAlias::create(json_decode($json, true));
        $this->assertEquals('file uploaded successfully', $model->getMessage());
    }
}
