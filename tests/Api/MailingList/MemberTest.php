<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api\MailingList;

use Mailgun\Api\MailingList;
use Mailgun\Exception\InvalidArgumentException;
use Mailgun\Tests\Api\TestCase;
use Nyholm\Psr7\Response;

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
        // Empty vars must go out as a JSON object, never `[]`: the API rejects
        // the array form with "'vars' parameter is not a valid JSON".
        $data = [
            'address' => 'foo@example.com',
            'name' => 'Foo',
            'vars' => '{}',
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

    public function testCreateWithVars()
    {
        $data = [
            'address' => 'foo@example.com',
            'name' => 'Foo',
            'vars' => '{"foo":"bar"}',
            'subscribed' => 'yes',
            'upsert' => 'no',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPost')
            ->with('/v3/lists/address/members', $data)
            ->willReturn(new Response());

        $api->create('address', 'foo@example.com', 'Foo', ['foo' => 'bar']);
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
            'members' => json_encode(
                [
                'bob@example.com',
                'foo@example.com',
                [
                    'address' => 'billy@example.com',
                    'name' => 'Billy',
                    'subscribed' => 'yes',
                ],
                ]
            ),
            'upsert' => 'no',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPost')
            ->with('/v3/lists/address/members.json', $data)
            ->willReturn(new Response());

        $api->createMultiple(
            $list = 'address', [
            'bob@example.com',
            'foo@example.com',
            [
                'address' => 'billy@example.com',
                'name' => 'Billy',
                'subscribed' => 'yes',
            ],
            ], false
        );
    }

    public function testCreateMultipleVarsStayJsonObjects()
    {
        // Inside the bulk payload, vars is nested in the members JSON: it must
        // encode as an object there, not as a JSON string (which the API
        // silently skips) and not as `[]` when empty (which the API rejects).
        $data = [
            'members' => '[{"address":"a@example.com","vars":{"foo":"bar"}},'
                . '{"address":"b@example.com","vars":{}},'
                . '{"address":"c@example.com","vars":{"baz":1}}]',
            'upsert' => 'no',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPost')
            ->with('/v3/lists/address/members.json', $data)
            ->willReturn(new Response());

        $api->createMultiple(
            'address',
            [
                ['address' => 'a@example.com', 'vars' => ['foo' => 'bar']],
                ['address' => 'b@example.com', 'vars' => []],
                ['address' => 'c@example.com', 'vars' => '{"baz":1}'],
            ]
        );
    }

    public function testCreateMultipleInvalidVarsString()
    {
        $this->expectException(InvalidArgumentException::class);

        $api = $this->getApiMock();
        $api->createMultiple(
            'address',
            [
                ['address' => 'a@example.com', 'vars' => 'not json'],
            ]
        );
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
                'subscribed' => 123,
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
            'vars' => \json_encode(
                [
                'foo' => 'bar',
                ]
            ),
            'subscribed' => 'yes',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPut')
            ->with('/v3/lists/address/members/member', $data)
            ->willReturn(new Response());

        $api->update('address', 'member', $data);
    }

    public function testUpdateEmptyVars()
    {
        $data = [
            'vars' => '{}',
            'subscribed' => 'yes',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPut')
            ->with('/v3/lists/address/members/member', $data)
            ->willReturn(new Response());

        $api->update('address', 'member', ['vars' => [], 'subscribed' => 'yes']);
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
