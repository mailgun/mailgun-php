<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api\MailingList;

use GuzzleHttp\Psr7\Response;
use Mailgun\Api\MailingList;
use Mailgun\Exception\InvalidArgumentException;
use Mailgun\Tests\Api\TestCase;

class MemberTest extends TestCase
{
    public function testIndexAll()
    {
        $data = [
            'limit' => 100,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/lists/address/members/pages', $data)
            ->willReturn(new Response());

        $api->index('address', 100, null);
    }

    public function testIndexSubscribed()
    {
        $data = [
            'limit' => 100,
            'subscribed' => 'yes',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/lists/address/members/pages', $data)
            ->willReturn(new Response());

        $api->index('address', 100, true);
    }

    public function testIndexUnsubscribed()
    {
        $data = [
            'limit' => 100,
            'subscribed' => 'no',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/lists/address/members/pages', $data)
            ->willReturn(new Response());

        $api->index('address', 100, false);
    }

    public function testCreate()
    {
        $data = [
            'address' => 'foo@example.com',
            'name' => 'Foo',
            'vars' => \json_encode([]),
            'subscribed' => 'yes',
            'upsert' => 'no',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPost')
            ->with('/v3/lists/address/members', $data)
            ->willReturn(new Response());

        $api->create($list = 'address', $address = 'foo@example.com', $name = 'Foo', $vars = [], $subscribed = true, $upsert = false);
    }

    public function testCreateInvalidAddress()
    {
        $api = $this->getApiMock();
        $this->expectException(InvalidArgumentException::class);
        $api->create('address', '');
    }

    public function testCreateInvalidSubscribed()
    {
        $api = $this->getApiMock();
        $this->expectException(InvalidArgumentException::class);
        $api->create('', 'foo@example.com');
    }

    public function testCreateMultiple()
    {
        $data = [
            'members' => json_encode([
                'bob@example.com',
                'foo@example.com',
                [
                    'address' => 'billy@example.com',
                    'name' => 'Billy',
                    'subscribed' => 'yes',
                ],
            ]),
            'upsert' => 'no',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPost')
            ->with('/v3/lists/address/members.json', $data)
            ->willReturn(new Response());

        $api->createMultiple($list = 'address', [
            'bob@example.com',
            'foo@example.com',
            [
                'address' => 'billy@example.com',
                'name' => 'Billy',
                'subscribed' => 'yes',
            ],
        ], false);
    }

    public function testCreateMultipleInvalidMemberArgument()
    {
        $this->expectException(InvalidArgumentException::class);

        $data = [
            'bob@example.com',
            'foo@example.com',
            [
                'address' => 'billy@example.com',
                'name' => 'Billy',
                'subscribed' => true,
            ],
        ];

        $api = $this->getApiMock();
        $api->createMultiple('address', $data);
    }

    public function testCreateMultipleCountMax1000()
    {
        $this->expectException(InvalidArgumentException::class);

        $members = range(1, 1001);
        $members = array_map('strval', $members);

        $api = $this->getApiMock();
        $api->createMultiple('address', $members);
    }

    public function testUpdate()
    {
        $data = [
            'vars' => \json_encode([
                'foo' => 'bar',
            ]),
            'subscribed' => 'yes',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPut')
            ->with('/v3/lists/address/members/member', $data)
            ->willReturn(new Response());

        $api->update('address', 'member', $data);
    }

    public function testUpdateInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);

        $data = [
            'vars' => 4711,
            'subscribed' => 'yes',
        ];

        $api = $this->getApiMock();
        $api->update('address', 'member', $data);
    }

    public function testDelete()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpDelete')
            ->with('/v3/lists/address/members/member')
            ->willReturn(new Response());

        $api->delete('address', 'member');
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiClass()
    {
        return MailingList\Member::class;
    }
}
