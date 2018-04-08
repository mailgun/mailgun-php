<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use GuzzleHttp\Psr7\Response;
use Mailgun\Api\MailingList;
use Mailgun\Exception\InvalidArgumentException;

/**
 * @author Michael Münch <helmchen@sounds-like.me>
 */
class MailingListTest extends TestCase
{
    public function testPages()
    {
        $data = [
            'limit' => 10,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/lists/pages', $data)
            ->willReturn(new Response());

        $api->pages(10);
    }

    public function testPagesInvalidArgument()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $api = $this->getApiMock();

        $limit = -1;

        $api->pages($limit);
    }

    public function testCreate()
    {
        $data = [
            'address' => 'foo@example.com',
            'name' => 'Foo',
            'description' => 'Description',
            'access_level' => 'readonly',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPost')
            ->with('/v3/lists', $data)
            ->willReturn(new Response());

        $api->create($address = 'foo@example.com', $name = 'Foo', $description = 'Description', $accessLevel = 'readonly');
    }

    public function testCreateInvalidAddress()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $api = $this->getApiMock();
        $api->create($address = '', $name = 'Foo', $description = 'Description', $accessLevel = 'readonly');
    }

    public function testCreateInvalidAccessLevel()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $api = $this->getApiMock();
        $api->create($address = '', $name = 'Foo', $description = 'Description', $accessLevel = 'admin');
    }

    public function testShow()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/lists/address')
            ->willReturn(new Response());

        $api->show('address');
    }

    public function testShowInvalidAddress()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $api = $this->getApiMock();
        $api->show('');
    }

    public function testUpdate()
    {
        $data = [
            'description' => 'desc',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPut')
            ->with('/v3/lists/address', $data)
            ->willReturn(new Response());

        $api->update('address', $data);
    }

    public function testUpdateInvalidArgument()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $data = [
            'access_level' => 'foo',
        ];

        $api = $this->getApiMock();
        $api->update('address', $data);
    }

    public function testDelete()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpDelete')
            ->with('/v3/lists/address')
            ->willReturn(new Response());

        $api->delete('address');
    }

    public function testMembersAll()
    {
        $data = [
            'limit' => 100,
            'subscribed' => null,
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/lists/address/members/pages', $data)
            ->willReturn(new Response());

        $api->members('address', 100, null);
    }

    public function testMembersSubscribed()
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

        $api->members('address', 100, 'yes');
    }

    public function testMembersUnsubscribed()
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

        $api->members('address', 100, 'no');
    }

    public function testCreateMember()
    {
        $data = [
            'address' => 'foo@example.com',
            'name' => 'Foo',
            'vars' => [],
            'subscribed' => 'yes',
            'upsert' => 'no',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPost')
            ->with('/v3/lists/address/members', $data)
            ->willReturn(new Response());

        $api->createMember($list = 'address', $address = 'foo@example.com', $name = 'Foo', $vars = [], $subscribed = 'yes', $upsert = 'no');
    }

    public function testCreateMemberInvalidAddress()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $api = $this->getApiMock();
        $api->createMember('address', '');
    }

    public function testCreateMemberInvalidVars()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $api = $this->getApiMock();
        $api->createMember('address', 'foo@example.com', null, '{foo: \'bar\'}');
    }

    public function testCreateMemberInvalidSubscribed()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $api = $this->getApiMock();
        $api->createMember('address', 'foo@example.com', null, [], true);
    }

    public function testUpdateMember()
    {
        $data = [
            'vars' => [
                'foo' => 'bar',
            ],
            'subscribed' => 'yes',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPut')
            ->with('/v3/lists/address/members/member', $data)
            ->willReturn(new Response());

        $api->updateMember('address', 'member', $data);
    }

    public function testUpdateMemberInvalidArgument()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $data = [
            'vars' => 'foo=bar',
            'subscribed' => 'yes',
        ];

        $api = $this->getApiMock();
        $api->updateMember('address', 'member', $data);
    }

    public function testDeleteMember()
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpDelete')
            ->with('/v3/lists/address/members/member')
            ->willReturn(new Response());

        $api->deleteMember('address', 'member');
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiClass()
    {
        return MailingList::class;
    }
}
