<?php

declare(strict_types=1);

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\ConnectionResponse;
use Mailgun\Tests\Model\BaseModelTest;

class ConnectionResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<JSON
{
  "connection": {
    "require_tls": false,
    "skip_verification": false
  }
}
JSON;
        $model = ConnectionResponse::create(json_decode($json, true));
        $this->assertFalse($model->getRequireTLS());
        $this->assertFalse($model->getSkipVerification());
    }
}
