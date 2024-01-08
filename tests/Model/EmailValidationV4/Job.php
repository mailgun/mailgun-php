<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\EmailValidationV4;

use Mailgun\Model\EmailValidationV4\Job as JobAlias;
use Mailgun\Model\EmailValidationV4\JobDownloadUrl;
use Mailgun\Model\EmailValidationV4\Summary;
use Mailgun\Tests\Model\BaseModel;

class Job extends BaseModel
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
    "created_at": 1590080191,
    "download_url": {
        "csv": "<download_link>",
        "json": "<download_link>"
    },
    "id": "bulk_validations_sandbox_mailgun_org",
    "quantity": 207,
    "records_processed": 208,
    "status": "uploaded",
    "summary": {
        "result": {
            "deliverable": 181854,
            "do_not_send": 5647,
            "undeliverable": 12116,
            "catch_all" : 2345,
            "unknown": 5613
        },
        "risk": {
            "high": 17763,
            "low": 142547,
            "medium": 41652,
            "unknown": 5613
        }
    }
}
JSON;
        $model = JobAlias::create(json_decode($json, true));
        $this->assertEquals('2020-05-21 16:56:31', $model->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(JobDownloadUrl::class, $model->getDownloadUrl());
        $this->assertEquals('bulk_validations_sandbox_mailgun_org', $model->getId());
        $this->assertEquals(207, $model->getQuantity());
        $this->assertEquals(208, $model->getRecordsProcessed());
        $this->assertEquals('uploaded', $model->getStatus());
        $this->assertInstanceOf(Summary::class, $model->getSummary());
    }
}
