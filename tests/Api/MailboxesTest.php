<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use GuzzleHttp\Psr7\Response;
use Mailgun\Api\Mailboxes;
use Mailgun\Exception\InvalidArgumentException;

class MailboxesTest extends TestCase
{
    public function testCreate()
    {
        $parameters = [
            'mailbox' => 'mailbox',
            'password' => 'password123',
        ];
        $domain = 'testing@domain.com';
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPost')
            ->with(sprintf('/v3/%s/mailboxes', $domain), $parameters)
            ->willReturn(new Response());

        $api->create($domain, $parameters);
    }

    public function testCreateInvalidPassword()
    {
        $this->expectException(InvalidArgumentException::class);
        $parameters = [
            'mailbox' => 'mailbox',
            'password' => 'pass',
        ];
        $domain = 'testing@domain.com';
        $api = $this->getApiMock();

        $api->create($domain, $parameters);
    }

    public function testShow()
    {
        $parameters = [
            'limit' => '2',
            'skip' => '4',
        ];
        $domain = 'testing@domain.com';
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpGet')
            ->with(sprintf('/v3/%s/mailboxes', $domain), $parameters)
            ->willReturn(new Response());

        $api->show($domain, $parameters);
    }

    public function testUpdate()
    {
        $parameters = [
            'password' => 'password123',
        ];
        $mailbox = 'mailboxname';
        $domain = 'testing@domain.com';
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPut')
            ->with(sprintf('/v3/%s/mailboxes/%s', $domain, $mailbox), $parameters)
            ->willReturn(new Response());

        $api->update($domain, $mailbox, $parameters);
    }

    public function testDelete()
    {
        $domain = 'testing@domain.com';
        $mailbox = 'mailboxname';
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpDelete')
            ->with(sprintf('/v3/%s/mailboxes/%s', $domain, $mailbox))
            ->willReturn(new Response());

        $api->delete($domain, $mailbox);
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiClass()
    {
        return Mailboxes::class;
    }
}
