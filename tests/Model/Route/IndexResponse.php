<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Route;

use Mailgun\Model\Route\IndexResponse as IndexResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class IndexResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "total_count": 266,
  "items": [
      {
          "description": "Sample route",
          "created_at": "Wed, 15 Feb 2012 12:58:12 GMT",
          "actions": [
              "forward(\"http://myhost.com/messages/\")",
              "stop()"
          ],
          "priority": 0,
          "expression": "match_recipient(\".*@samples.mailgun.org\")",
          "id": "4f3babe4ba8a481c6400476a"
      }
  ]
}
JSON;
        $model = IndexResponseAlias::create(json_decode($json, true));

        $this->assertEquals('266', $model->getTotalCount());
        $routes = $model->getRoutes();
        $this->assertCount(1, $routes);
        $route = $routes[0];
        $this->assertEquals('Sample route', $route->getDescription());
    }
}
