<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\CreateResponse;
use Mailgun\Model\Domain\DeleteCredentialResponse;
use Mailgun\Model\Domain\DeleteResponse;
use Mailgun\Model\Domain\DnsRecord;
use Mailgun\Model\Domain\Domain;
use Mailgun\Model\Domain\IndexResponse;
use Mailgun\Model\Domain\ShowResponse;
use Mailgun\Model\Domain\UpdateConnectionResponse;
use Mailgun\Tests\Model\BaseModelTest;

class UpdateConnectionResponseTest extends BaseModelTest
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
        $model = UpdateConnectionResponse::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
        $this->assertTrue($model->getRequireTLS());
        $this->assertFalse($model->getSkipVerification());
    }
}
