<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain\Tracking;

use Mailgun\Model\Domain\Domain;
use Mailgun\Model\Domain\Tracking\TrackingResponse;
use Mailgun\Tests\Model\BaseModelTest;

class TrackingResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "tracking": {
    "click": {
      "active": true
    },
    "open": {
      "active": true
    },
    "unsubscribe": {
      "active": false,
      "html_footer": "<br>\n<p><a href=\"%unsubscribe_url%\">unsubscribe</a></p>",
      "text_footer": "To unsubscribe click: <%unsubscribe_url%>"
    }
  }
}
JSON;
        $model = TrackingResponse::create(json_decode($json, true));
        $click = $model->getClick();
        $this->assertTrue($click->isActive());
        $this->assertFalse($click->isHtmlOnly());

        $open = $model->getOpen();
        $this->assertTrue($open->isActive());

        $unsubscribe = $model->getUnsubscribe();
        $this->assertFalse($unsubscribe->isActive());
        $this->assertNotEmpty($unsubscribe->getHtmlFooter());
        $this->assertNotEmpty($unsubscribe->getTextFooter());
    }
}
