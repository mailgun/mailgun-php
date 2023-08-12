<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Message;

use Mailgun\Model\Message\SendResponse;
use Mailgun\Tests\Model\BaseModelTest;

class SendResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "message": "Queued. Thank you.",
  "id": "<20111114174239.25659.5817@samples.mailgun.org>"
}
JSON;
        $model = SendResponse::create(json_decode($json, true));
        $this->assertEquals('<20111114174239.25659.5817@samples.mailgun.org>', $model->getId());
        $this->assertEquals('Queued. Thank you.', $model->getMessage());
    }
}
