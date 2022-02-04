<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\EmailValidation;
use Nyholm\Psr7\Response;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
class EmailValidationTest extends TestCase
{
    protected function getApiClass()
    {
        return EmailValidation::class;
    }

    public function testValidEmail()
    {
        $params = [
            'address' => 'me@davidgarcia.cat',
            'mailbox_verification' => true,
        ];

        $api = $this->getApiMock();

        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/address/private/validate', $params)
            ->willReturn(new Response());

        $api->validate($params['address'], $params['mailbox_verification']);
    }

    public function testParseEmail()
    {
        $params = [
            'addresses' => 'me@davidgarcia.cat',
            'syntax_only' => true,
        ];

        $api = $this->getApiMock();

        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v3/address/private/parse', $params)
            ->willReturn(new Response());

        $api->parse($params['addresses'], $params['syntax_only']);
    }
}
