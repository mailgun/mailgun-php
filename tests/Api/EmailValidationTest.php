<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use GuzzleHttp\Psr7\Response;
use Mailgun\Api\EmailValidation;
use Mailgun\Hydrator\ModelHydrator;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
class EmailValidationTest extends TestCase
{
    protected function getApiClass()
    {
        return EmailValidation::class;
    }

    public function testRFCValidation()
    {
    }

    public function testDNSCheckValidation()
    {
    }

    public function testSpoofCheckValidation()
    {
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
            ->with('/address/private/validate', $params)
            ->willReturn(new Response());

        $api->validate($params['address'], $params['mailbox_verification']);
    }
}
