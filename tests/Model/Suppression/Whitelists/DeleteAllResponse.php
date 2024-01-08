<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Suppression\Whitelists;

use Mailgun\Model\Suppression\Whitelist\DeleteAllResponse as DeleteAllResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class DeleteAllResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "message": "Whitelist addresses/domains for this domain have been removed"
}
JSON;

        $model = DeleteAllResponseAlias::create(json_decode($json, true));
        $this->assertEquals('Whitelist addresses/domains for this domain have been removed', $model->getMessage());
    }
}
