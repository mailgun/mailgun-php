<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\Event;
use Mailgun\Exception\InvalidArgumentException;
use Mailgun\Model\Event\EventResponse;
use Nyholm\Psr7\Response;

class EventTest extends TestCase
{
    protected function getApiClass()
    {
        return Event::class;
    }

    public function testGet()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/example.com/events');
        $this->setHttpResponse(new Response(200, ['Content-Type' => 'application/json'], <<<'JSON'
{
  "items": [
    {
      "tags": [],
      "id": "czsjqFATSlC3QtAK-C80nw",
      "timestamp": 1376325780.160809,
      "envelope": {
        "sender": "me@samples.mailgun.org",
        "transport": ""
      },
      "event": "accepted",
      "campaigns": [],
      "user-variables": {},
      "flags": {
        "is-authenticated": true,
        "is-test-mode": false
      },
      "message": {
        "headers": {
          "to": "foo@example.com",
          "message-id": "20130812164300.28108.52546@samples.mailgun.org",
          "from": "Excited User <me@samples.mailgun.org>",
          "subject": "Hello"
        },
        "attachments": [],
        "recipients": [
          "foo@example.com",
          "baz@example.com",
          "bar@example.com"
        ],
        "size": 69
      },
      "recipient": "baz@example.com",
      "method": "http"
    }
  ],
  "paging": {
    "next":
        "https://api.mailgun.net/v3/samples.mailgun.org/events/W3siY...",
    "previous":
        "https://api.mailgun.net/v3/samples.mailgun.org/events/Lkawm..."
  }
}
JSON
        ));

        $api = $this->getApiInstance();
        $event = $api->get('example.com');
        $this->assertInstanceOf(EventResponse::class, $event);
        $this->assertCount(1, $event->getItems());
        $this->assertEquals('accepted', $event->getItems()[0]->getEvent());
    }

    public function testGetWithEmptyDomain()
    {
        $api = $this->getApiMock();
        $this->expectException(InvalidArgumentException::class);
        $api->get('');
    }
}
