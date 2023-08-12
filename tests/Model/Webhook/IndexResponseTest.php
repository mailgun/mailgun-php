<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Webhook;

use Mailgun\Model\Webhook\IndexResponse;
use Mailgun\Tests\Model\BaseModelTest;

class IndexResponseTest extends BaseModelTest
{
    public function testCurrent()
    {
        $json =
        <<<'JSON'
{
  "webhooks": {
    "clicked": {
      "urls": [
        "http:\/\/example.com\/clicked_1"
      ]
    },
    "complained": {
      "urls": [ 
        "http:\/\/example.com\/complained_1"
      ]
    },
    "delivered": {
       "urls": [
        "http:\/\/example.com\/delivered_1"
       ]
    },
    "opened": {
      "urls": [
        "http:\/\/example.com\/opened_1"
      ]
    },
    "permanent_fail": {
      "urls": [
        "http:\/\/example.com\/permanent_fail_1"
      ]
    },
    "temporary_fail": {
      "urls": [
        "http:\/\/example.com\/temporary_fail_1"
      ]
    },
    "unsubscribed": {
      "urls": [
        "http:\/\/example.com\/unsubscribed_1"
      ]
    }
  }
}

JSON;
        $model = IndexResponse::create(json_decode($json, true));

        $this->assertContains('http://example.com/clicked_1', $model->getClickedUrls());
        $this->assertContains('http://example.com/complained_1', $model->getComplainedUrls());
        $this->assertContains('http://example.com/delivered_1', $model->getDeliveredUrls());
        $this->assertContains('http://example.com/opened_1', $model->getOpenedUrls());
        $this->assertContains('http://example.com/permanent_fail_1', $model->getPermanentFailUrls());
        $this->assertContains('http://example.com/temporary_fail_1', $model->getTemporaryFailUrls());
        $this->assertContains('http://example.com/unsubscribed_1', $model->getUnsubscribeUrls());
        $this->assertNull($model->getBounceUrl());
        $this->assertNull($model->getDeliverUrl());
        $this->assertNull($model->getDropUrl());
        $this->assertNull($model->getSpamUrl());
        $this->assertNull($model->getUnsubscribeUrl());
        $this->assertNull($model->getClickUrl());
        $this->assertNull($model->getOpenUrl());
    }

    public function testLegacy()
    {
        $json =
        <<<'JSON'
{
  "webhooks": {
    "click": {
      "url": "http:\/\/example.com\/click_1"
    },
    "bounce": {
      "url": "http:\/\/example.com\/bounce_1"
    },
    "deliver": {
      "url": "http:\/\/example.com\/deliver_1"
    },
    "drop": {
      "url": "http:\/\/example.com\/drop_1"
    },
    "open": {
      "url": "http:\/\/example.com\/open_1"
    },
    "spam": {
      "url": "http:\/\/example.com\/spam_1"
    },
    "unsubscribe": {
      "url": "http:\/\/example.com\/unsubscribe_1"
    }
  }
}

JSON;
        $model = IndexResponse::create(json_decode($json, true));

        $this->assertEquals('http://example.com/click_1', $model->getClickUrl());
        $this->assertEquals('http://example.com/bounce_1', $model->getBounceUrl());
        $this->assertEquals('http://example.com/deliver_1', $model->getDeliverUrl());
        $this->assertEquals('http://example.com/drop_1', $model->getDropUrl());
        $this->assertEquals('http://example.com/open_1', $model->getOpenUrl());
        $this->assertEquals('http://example.com/spam_1', $model->getSpamUrl());
        $this->assertEquals('http://example.com/unsubscribe_1', $model->getUnsubscribeUrl());
        $this->assertIsArray($model->getClickedUrls());
        $this->assertIsArray($model->getComplainedUrls());
        $this->assertIsArray($model->getDeliveredUrls());
        $this->assertIsArray($model->getOpenedUrls());
        $this->assertIsArray($model->getPermanentFailUrls());
        $this->assertIsArray($model->getTemporaryFailUrls());
        $this->assertIsArray($model->getUnsubscribeUrls());
        $this->assertCount(0, $model->getClickedUrls());
        $this->assertCount(0, $model->getComplainedUrls());
        $this->assertCount(0, $model->getDeliveredUrls());
        $this->assertCount(0, $model->getOpenedUrls());
        $this->assertCount(0, $model->getPermanentFailUrls());
        $this->assertCount(0, $model->getTemporaryFailUrls());
        $this->assertCount(0, $model->getUnsubscribeUrls());
    }
}
