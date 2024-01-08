<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Ip;

use Mailgun\Model\Ip\ShowResponse as ShowResponseAlias;
use Mailgun\Tests\Model\BaseModel;

class ShowResponse extends BaseModel
{
    public function testCreate()
    {
        $json =
        <<<'JSON'
{
  "ip": "192.161.0.1",
  "dedicated": true,
  "rdns": "luna.mailgun.net"
}
JSON;
        $model = ShowResponseAlias::create(json_decode($json, true));
        $this->assertEquals('192.161.0.1', $model->getIp());
        $this->assertTrue($model->getDedicated());
        $this->assertEquals('luna.mailgun.net', $model->getRdns());
    }
}
