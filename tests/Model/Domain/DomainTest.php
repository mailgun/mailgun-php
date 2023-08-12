<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\Domain;
use Mailgun\Tests\Model\BaseModelTest;

class DomainTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
    "name": "example.com",
    "created_at": "Fri, 22 Nov 2013 18:42:33 GMT",
    "wildcard": false,
    "spam_action": "disabled",
    "smtp_login": "postmaster@example.com",
    "smtp_password": "thiswontwork",
    "state": "active"
}
JSON;
        $model = Domain::create(json_decode($json, true));
        $this->assertNotEmpty($model->getName());
        $this->assertNotEmpty($model->getCreatedAt());
        $this->assertFalse($model->isWildcard());
        $this->assertNotEmpty($model->getSpamAction());
        $this->assertNotEmpty($model->getSmtpPassword());
        $this->assertNotEmpty($model->getSmtpUsername());
        $this->assertNotEmpty($model->getState());
    }
}
