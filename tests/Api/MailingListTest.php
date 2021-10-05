<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\MailingList;
use Mailgun\Exception\InvalidArgumentException;
use Mailgun\Model\EmailValidation\ValidateResponse;
use Mailgun\Model\MailingList\ValidationCancelResponse;
use Mailgun\Model\MailingList\ValidationStatusResponse;
use Nyholm\Psr7\Response;

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
        $this->expectException(InvalidArgumentException::class);
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
            'reply_preference' => 'list',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPost')
            ->with('/v3/lists', $data)
            ->willReturn(new Response());

        $api->create($address = 'foo@example.com', $name = 'Foo', $description = 'Description', $accessLevel = 'readonly');
    }

    public function testCreateWithNulls()
    {
        $data = [
            'address' => 'foo@example.com',
            'access_level' => 'readonly',
            'reply_preference' => 'list',
        ];

        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('httpPost')
            ->with('/v3/lists', $data)
            ->willReturn(new Response());

        $api->create($address = 'foo@example.com', null, null, $accessLevel = 'readonly');
    }

    public function testCreateInvalidAddress()
    {
        $this->expectException(InvalidArgumentException::class);

        $api = $this->getApiMock();
        $api->create($address = '', $name = 'Foo', $description = 'Description', $accessLevel = 'readonly');
    }

    public function testCreateInvalidAccessLevel()
    {
        $this->expectException(InvalidArgumentException::class);

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
        $this->expectException(InvalidArgumentException::class);

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
        $this->expectException(InvalidArgumentException::class);

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

    public function testValidate()
    {
        $this->setRequestMethod('POST');
        $this->setRequestUri('/v3/lists/address@domain/validate');
        $this->setHydrateClass(ValidateResponse::class);

        $api = $this->getApiInstance();
        $api->validate('address@domain');
    }

    public function testValidationStatus()
    {
        $this->setRequestMethod('GET');
        $this->setRequestUri('/v3/lists/address@domain/validate');
        $this->setHydrateClass(ValidationStatusResponse::class);

        $api = $this->getApiInstance();
        $api->getValidationStatus('address@domain');
    }

    public function testCancelValidate()
    {
        $this->setRequestMethod('DELETE');
        $this->setRequestUri('/v3/lists/address@domain/validate');
        $this->setHydrateClass(ValidationCancelResponse::class);

        $api = $this->getApiInstance();
        $api->cancelValidation('address@domain');
    }

    /**
     * {@inheritdoc}
     */
    protected function getApiClass()
    {
        return MailingList::class;
    }
}
