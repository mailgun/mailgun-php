<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Webhook;

use Mailgun\Model\Webhook\ShowResponse;
use Mailgun\Tests\Model\BaseModelTest;

class ShowResponseTest extends BaseModelTest
{
    public function testHook1()
    {
        $json =
        <<<'JSON'
{
  "webhook": {
    "urls": [
      "http:\/\/example.com\/hook_1"
    ]
  }
}

JSON;
        $model = ShowResponse::create(json_decode($json, true));

        $this->assertContains('http://example.com/hook_1', $model->getWebhookUrls());
    }
}
