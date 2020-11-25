<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Event;

use Mailgun\Model\EmailValidationV4\Job;
use Mailgun\Model\EmailValidationV4\JobDownloadUrl;
use Mailgun\Model\EmailValidationV4\Summary;
use Mailgun\Tests\Model\BaseModelTest;

class JobDownloadUrlTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "csv": "https://example.com/file.csv",
    "json": "https://example.com/file.json"
}
JSON;
        $model = JobDownloadUrl::create(json_decode($json, true));
        $this->assertEquals('https://example.com/file.csv', $model->getCsv());
        $this->assertEquals('https://example.com/file.json', $model->getJson());
    }
}
