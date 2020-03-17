<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain\Tracking;

use Mailgun\Model\Domain\Tracking\UpdateUnsubscribeTrackingResponse;
use Mailgun\Tests\Model\BaseModelTest;

class UpdateUnsubscribeTrackingResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
{
  "unsubscribe": {
    "active": true,
    "html_footer": "<br>\n<p><a href=\"%unsubscribe_url%\">unsubscribe</a></p>",
    "text_footer": "To unsubscribe click: <%unsubscribe_url%>"
  },
  "message": "Domain tracking settings have been updated"
}
JSON;
        $model = UpdateUnsubscribeTrackingResponse::create(json_decode($json, true));
        $this->assertNotEmpty($model->getMessage());

        $unsubscribe = $model->getUnsubscribe();
        $this->assertTrue($unsubscribe->isActive());
        $this->assertNotEmpty($unsubscribe->getHtmlFooter());
        $this->assertNotEmpty($unsubscribe->getTextFooter());
    }
}
