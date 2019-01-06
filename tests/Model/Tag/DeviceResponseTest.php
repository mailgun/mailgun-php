<?php

declare(strict_types=1);

namespace Mailgun\Tests\Model\Tag;

use Mailgun\Model\Tag\DeviceResponse;
use Mailgun\Model\Tag\ProviderResponse;
use Mailgun\Tests\Model\BaseModelTest;

class ProviderResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "devices": {
      "desktop": {
          "clicked": 8,
          "complained": 1,
          "opened": 8,
          "unique_clicked": 0,
          "unique_opened": 0,
          "unsubscribed": 0
      },
      "mobile": {
          "clicked": 3,
          "complained": 1,
          "opened": 5,
          "unique_clicked": 0,
          "unique_opened": 0,
          "unsubscribed": 0
      }
  },
  "tag": "exampletag"
}
JSON;
        $model = DeviceResponse::create(json_decode($json, true));

        $this->assertCount(2, $model->getDevices());
        $this->assertEquals('exampletag', $model->getTag());
    }
}
