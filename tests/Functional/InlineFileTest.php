<?php

namespace Mailgun\Tests\Functional;

/**
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class InlineFileTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleExample()
    {
        $fileValidator = function($files) {
            $fileNames = [
                ['name'=>'inline[0]', 'filename'=>'foo.png'],
                ['name'=>'inline[1]', 'filename'=>'bar.png']
            ];

            // Make sure that both files exists
            foreach ($fileNames as $idx => $fileName) {
                foreach ($files as $file) {
                    if ($file['name'] === $fileName['name'] && $file['filename'] === $fileName['filename']) {
                        unset ($fileNames[$idx]);
                        break;
                    }
                }
            }

            $this->assertEmpty($fileNames, 'Filenames could not be found');
        };

        // Create the mocked mailgun client. We use $this->assertEquals on $method, $uri and $body parameters.
        $mailgun = MockedMailgun::create($this, 'POST', 'domain/messages', [], $fileValidator);

        $builder = $mailgun->MessageBuilder();
        $builder->setFromAddress("bob@example.com");
        $builder->addToRecipient("alice@example.com");
        $builder->setSubject("Foo");
        $builder->setTextBody("Bar");

        $builder->addInlineImage("@./tests/TestAssets/mailgun_icon1.png", 'foo.png');
        $builder->addInlineImage("@./tests/TestAssets/mailgun_icon2.png", 'bar.png');

        $mailgun->post("domain/messages", $builder->getMessage(), $builder->getFiles());
    }
}
