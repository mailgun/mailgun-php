<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\MailingList;

use Mailgun\Model\MailingList\ValidationStatusDownloadUrl;
use Mailgun\Model\MailingList\ValidationStatusResponse;
use Mailgun\Model\MailingList\ValidationStatusSummary;
use Mailgun\Model\MailingList\ValidationStatusSummaryResult;
use Mailgun\Model\MailingList\ValidationStatusSummaryRisk;
use Mailgun\Tests\Model\BaseModelTest;

class ValidationStatusResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
    "created_at": "Tue, 26 Feb 2019 21:30:03 GMT",
    "download_url": {
        "csv": "http://example.com/filname.csv",
        "json": "http://example.com/filname.json"
    },
    "id": "listname@mydomain.sandbox.mailgun.org",
    "quantity": 207665,
    "records_processed": 207665,
    "status": "uploaded",
    "summary": {
        "result": {
            "deliverable": 184199,
            "do_not_send": 5647,
            "undeliverable": 12116,
            "unknown": 5613
        },
        "risk": {
            "high": 17763,
            "low": 142547,
            "medium": 41652,
            "unknown": 5614
        }
    }
}
JSON;
        $model = ValidationStatusResponse::create(json_decode($json, true));
        $this->assertEquals('Tue, 26 Feb 2019 21:30:03 GMT', $model->getCreatedAt());
        $this->assertInstanceOf(ValidationStatusDownloadUrl::class, $model->getDownloadUrl());
        $this->assertEquals('http://example.com/filname.csv', $model->getDownloadUrl()->getCsv());
        $this->assertEquals('http://example.com/filname.json', $model->getDownloadUrl()->getJson());
        $this->assertEquals('listname@mydomain.sandbox.mailgun.org', $model->getId());
        $this->assertEquals(207665, $model->getQuantity());
        $this->assertEquals(207665, $model->getRecordsProcessed());
        $this->assertEquals('uploaded', $model->getStatus());
        $this->assertInstanceOf(ValidationStatusSummary::class, $model->getSummary());
        $this->assertInstanceOf(ValidationStatusSummaryResult::class, $model->getSummary()->getResult());
        $this->assertEquals(184199, $model->getSummary()->getResult()->getDeliverable());
        $this->assertEquals(5647, $model->getSummary()->getResult()->getDoNotSend());
        $this->assertEquals(12116, $model->getSummary()->getResult()->getUndeliverable());
        $this->assertEquals(5613, $model->getSummary()->getResult()->getUnknown());
        $this->assertInstanceOf(ValidationStatusSummaryRisk::class, $model->getSummary()->getRisk());
        $this->assertEquals(17763, $model->getSummary()->getRisk()->getHigh());
        $this->assertEquals(142547, $model->getSummary()->getRisk()->getLow());
        $this->assertEquals(41652, $model->getSummary()->getRisk()->getMedium());
        $this->assertEquals(5614, $model->getSummary()->getRisk()->getUnknown());
    }
}
