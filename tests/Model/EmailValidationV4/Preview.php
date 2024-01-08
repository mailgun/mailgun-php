<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\EmailValidationV4;

use Mailgun\Model\EmailValidationV4\Preview as PreviewAlias;
use Mailgun\Model\EmailValidationV4\Summary;
use Mailgun\Tests\Model\BaseModel;

class Preview extends BaseModel
{
    public function testCreate()
    {
        $json =
            <<<'JSON'
{
  "id": "test_500",
  "valid": true,
  "status": "preview_complete",
  "quantity": 8,
  "created_at": 1590080191,
  "summary": {
    "result": {
      "deliverable": 37.5,
      "do_not_send": 0,
      "undeliverable": 23,
      "catch_all": 2,
      "unknown": 37.5
    },
    "risk": {
      "high": 25,
      "low": 25,
      "medium": 12.5,
      "unknown": 37.5
    }
  }
}
JSON;
        $model = PreviewAlias::create(json_decode($json, true));
        $this->assertEquals('test_500', $model->getId());
        $this->assertEquals(true, $model->isValid());
        $this->assertEquals('preview_complete', $model->getStatus());
        $this->assertEquals(8, $model->getQuantity());
        $this->assertEquals('1590080191', $model->getCreatedAt()->format('U'));
        $this->assertInstanceOf(Summary::class, $model->getSummary());
    }
}
