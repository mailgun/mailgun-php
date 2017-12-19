<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Messages;

use Mailgun\Tests\Mock\Mailgun;

class StandardMessageTest extends \Mailgun\Tests\MailgunTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = new Mailgun('My-Super-Awesome-API-Key');
    }

    public function testSendMIMEMessage()
    {
        $customMime = 'Received: by luna.mailgun.net with SMTP mgrt 8728174999085; Mon, 10 Jun 2013 09:50:58 +0000
					Mime-Version: 1.0
					Content-Type: text/plain; charset="ascii"
					Subject: This is the Subject!
					From: Mailgun Testing <test@test.mailgun.com>
					To: test@test.mailgun.com
					Message-Id: <20130610095049.30790.4334@test.mailgun.com>
					Content-Transfer-Encoding: 7bit
					X-Mailgun-Sid: WyIxYTdhMyIsICJmaXplcmtoYW5AcXVhZG1zLmluIiwgImExOWQiXQ==
					Date: Mon, 10 Jun 2013 09:50:58 +0000
					Sender: test@test.mailgun.com

					Mailgun is testing!';
        $envelopeFields = ['to' => 'test@test.mailgun.org'];
        $result = $this->client->sendMessage('test.mailgun.org', $envelopeFields, $customMime);
        $this->assertEquals('test.mailgun.org/messages.mime', $result->http_endpoint_url);
    }

    public function testSendMessage()
    {
        $message = ['to' => 'test@test.mailgun.org',
                         'from' => 'sender@test.mailgun.org',
                         'subject' => 'This is my test subject',
                         'text' => 'Testing!',
        ];
        $result = $this->client->sendMessage('test.mailgun.org', $message);
        $this->assertEquals('test.mailgun.org/messages', $result->http_endpoint_url);
    }
}
