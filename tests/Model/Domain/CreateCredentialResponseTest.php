<?php

declare(strict_types=1);

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\ConnectionResponse;
use Mailgun\Model\Domain\CreateCredentialResponse;
use Mailgun\Tests\Model\BaseModelTest;

class CreateCredentialResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<JSON
{
  "message": "Created 1 credentials pair(s)"
}
JSON;
        $model = CreateCredentialResponse::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());
    }
}
