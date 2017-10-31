<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Functional;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class InlineFileTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleExample()
    {
        $fileValidator = function ($files) {
            $fileNames = [
                ['name' => 'inline', 'filename' => 'foo.png'],
                ['name' => 'inline', 'filename' => 'bar.png'],
            ];

            // Make sure that both files exists
            foreach ($fileNames as $idx => $fileName) {
                foreach ($files as $file) {
                    if ($file['name'] === $fileName['name'] && $file['filename'] === $fileName['filename']) {
                        unset($fileNames[$idx]);

                        break;
                    }
                }
            }

            $this->assertEmpty($fileNames, 'Filenames could not be found');
        };

        // Create the mocked mailgun client. We use $this->assertEquals on $method, $uri and $body parameters.
        $mailgun = MockedMailgun::createMock($this, 'POST', 'domain/messages', [], $fileValidator);

        $builder = $mailgun->MessageBuilder();
        $builder->setFromAddress('bob@example.com');
        $builder->addToRecipient('alice@example.com');
        $builder->setSubject('Foo');
        $builder->setTextBody('Bar');

        $builder->addInlineImage('@./tests/TestAssets/mailgun_icon1.png', 'foo.png');
        $builder->addInlineImage('@./tests/TestAssets/mailgun_icon2.png', 'bar.png');

        $mailgun->post('domain/messages', $builder->getMessage(), $builder->getFiles());
    }
}
