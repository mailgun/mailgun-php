<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Model\Event;

use Mailgun\Model\Event\EventResponse;
use Mailgun\Tests\Model\BaseModelTest;

class EventResponseTest extends BaseModelTest
{
    public function testCreate()
    {
        $json =
<<<'JSON'
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
        "https://api.mailgun.net/v3/samples.mailgun.org/events/W3siY",
    "previous":
        "https://api.mailgun.net/v3/samples.mailgun.org/events/Lkawm"
  }
}
JSON;
        $model = EventResponse::create(json_decode($json, true));
        $events = $model->getItems();
        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertEquals('czsjqFATSlC3QtAK-C80nw', $event->getId());

        // Should correctly encode Event model to json
        $encodedEvent = json_encode($event);

        // Should decode previously encoded model to associative array
        $decodedEvent = json_decode($encodedEvent, true);

        // Check that all properties was correctly json_encode`d
        $this->assertEquals($event->getEvent(), $decodedEvent['event']);
        $this->assertEquals($event->getId(), $decodedEvent['id']);
        $this->assertEquals($event->getTimestamp(), $decodedEvent['timestamp']);
        $this->assertEquals((array)$event->getEventDate(), $decodedEvent['eventDate']);
        $this->assertEquals($event->getTags(), $decodedEvent['tags']);
        $this->assertEquals($event->getUrl(), $decodedEvent['url']);
        $this->assertEquals($event->getSeverity(), $decodedEvent['severity']);
        $this->assertEquals($event->getEnvelope(), $decodedEvent['envelope']);
        $this->assertEquals($event->getDeliveryStatus(), $decodedEvent['deliveryStatus']);
        $this->assertEquals($event->getCampaigns(), $decodedEvent['campaigns']);
        $this->assertEquals($event->getIp(), $decodedEvent['ip']);
        $this->assertEquals($event->getClientInfo(), $decodedEvent['clientInfo']);
        $this->assertEquals($event->getReason(), $decodedEvent['reason']);
        $this->assertEquals($event->getUserVariables(), $decodedEvent['userVariables']);
        $this->assertEquals($event->getFlags(), $decodedEvent['flags']);
        $this->assertEquals($event->getRoutes(), $decodedEvent['routes']);
        $this->assertEquals($event->getMessage(), $decodedEvent['message']);
        $this->assertEquals($event->getRecipient(), $decodedEvent['recipient']);
        $this->assertEquals($event->getGeolocation(), $decodedEvent['geolocation']);
        $this->assertEquals($event->getStorage(), $decodedEvent['storage']);
        $this->assertEquals($event->getMethod(), $decodedEvent['method']);
    }
}
