<?php

namespace Mailgun\Tests\Api;

use Mailgun\Api\Event;

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

        $api = $this->getApiMock();
        $api->get('example.com');
    }
}
