<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\EmailValidation;

use Mailgun\Model\EmailValidation\Parts as PartsAlias;
use Mailgun\Tests\Model\BaseModel;

class Parts extends BaseModel
{
    public function testPartsConstructor()
    {
        $data = [
            'display_name' => ' Display name',
            'domain' => 'Domain',
            'local_part' => 'Local Part',
        ];

        $parts = PartsAlias::create($data);

        $this->assertEquals($data['display_name'], $parts->getDisplayName());
        $this->assertEquals($data['domain'], $parts->getDomain());
        $this->assertEquals($data['local_part'], $parts->getLocalPart());
    }
}
