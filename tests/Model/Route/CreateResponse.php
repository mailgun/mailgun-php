<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Route;

use Mailgun\Model\Route\CreateResponse as CreateResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class CreateResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "message": "Route has been created",
  "route": {
      "description": "Sample route",
      "created_at": "Wed, 15 Feb 2012 13:03:31 GMT",
      "actions": [
          "forward(\"http://myhost.com/messages/\")",
          "stop()"
      ],
      "priority": 0,
      "expression": "match_recipient(\".*@samples.mailgun.org\")",
      "id": "4f3bad2335335426750048c6"
  }
}
JSON;
        $model = CreateResponseAlias::create(json_decode($json, true));

        $this->assertEquals('Route has been created', $model->getMessage());
        $route = $model->getRoute();
        $this->assertEquals('Sample route', $route->getDescription());
    }
}
