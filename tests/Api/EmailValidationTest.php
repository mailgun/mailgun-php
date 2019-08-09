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
use Mailgun\Api\EmailValidation;

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
        $params = ['address' => 'foo@mailgun.net'];

        $api = $this->getApiMock();

        $api->expects($this->once())
            ->method('httpGet')
            ->with('/v4/address/validate', $params)
            ->willReturn(new Response());

        $api->validate($params['address']);
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
            ->with('/address/private/parse', $params)
            ->willReturn(new Response());

        $api->parse($params['addresses'], $params['syntax_only']);
    }
}
