<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\ClickTracking;
use Mailgun\Model\Domain\OpenTracking;
use Mailgun\Model\Domain\TrackingResponse;
use Mailgun\Model\Domain\UnsubscribeTracking;
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
            "active": "htmlonly"
        },
        "open": {
            "active": "no"
        },
        "unsubscribe": {
            "active": false,
            "html_footer": "<s>Test<\/s>",
            "text_footer": "Test"
        }
    }
}
JSON;
        $model = TrackingResponse::create(json_decode($json, true));
        $this->assertNotEmpty($model->getClick());
        $this->assertInstanceOf(ClickTracking::class, $model->getClick());
        $this->assertEquals('htmlonly', $model->getClick()->getActive());
        $this->assertFalse($model->getClick()->isActive());
        $this->assertTrue($model->getClick()->isHtmlOnly());

        $this->assertNotEmpty($model->getOpen());
        $this->assertInstanceOf(OpenTracking::class, $model->getOpen());
        $this->assertEquals('no', $model->getOpen()->getActive());
        $this->assertFalse($model->getOpen()->isActive());

        $this->assertNotEmpty($model->getUnsubscribe());
        $this->assertInstanceOf(UnsubscribeTracking::class, $model->getUnsubscribe());
        $this->assertEquals('false', $model->getUnsubscribe()->getActive());
        $this->assertFalse($model->getUnsubscribe()->isActive());
        $this->assertEquals('<s>Test</s>', $model->getUnsubscribe()->getHtmlFooter());
        $this->assertEquals('Test', $model->getUnsubscribe()->getTextFooter());
    }
}
