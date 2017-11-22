<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Functional;

/**
 * @author James Kraus <jkraus@imagineteam.com>
 */
class MessageBuilderHeaderTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleExample()
    {
        $messageValidator = function ($headers) {
            $this->assertContains(['name' => 'h:My-Singular-Header', 'contents' => '123'], $headers);
            $this->assertContains(['name' => 'h:My-Plural-Header', 'contents' => '123'], $headers);
            $this->assertContains(['name' => 'h:My-Plural-Header', 'contents' => '456'], $headers);
        };

        // Create the mocked mailgun client.
        $mailgun = MockedMailgun::createMock($this, 'POST', 'domain/messages', [], $messageValidator);

        $builder = $mailgun->MessageBuilder();

        $builder->addCustomHeader('My-Singular-Header', '123');
        $builder->addCustomHeader('My-Plural-Header', '123');
        $builder->addCustomHeader('My-Plural-Header', '456');

        $builder->setFromAddress('from@example.com');
        $builder->addToRecipient('to@example.com');
        $builder->setSubject('Foo');
        $builder->setTextBody('Bar');

        $mailgun->sendMessage('domain', $builder->getMessage());
    }
}
