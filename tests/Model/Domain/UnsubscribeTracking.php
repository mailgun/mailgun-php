<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Domain;

use Mailgun\Model\Domain\UnsubscribeTracking as UnsubscribeTrackingAlias;
use Mailgun\Tests\Model\BaseModel;

class UnsubscribeTracking extends BaseModel
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "active": true,
    "html_footer": "<s>Test<\/s>",
    "text_footer": "Test"
}
JSON;
        $model = UnsubscribeTrackingAlias::create(json_decode($json, true));
        $this->assertTrue($model->isActive());
        $this->assertEquals('<s>Test</s>', $model->getHtmlFooter());
        $this->assertEquals('Test', $model->getTextFooter());
    }
}
