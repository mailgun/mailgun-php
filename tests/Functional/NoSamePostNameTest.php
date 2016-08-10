<?php

namespace Mailgun\Tests\Functional;

/**
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class NoSamePostNameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * No post names should ever be the same
     */
    public function testNames()
    {
        $fileValidator = function($files) {
            $usedNames = [];
            foreach ($files as $file) {
                $this->assertFalse(in_array($file['name'], $usedNames), 'No files should have the same POST name.');
                $usedNames[] = $file['name'];
            }
        };

        // Create the mocked mailgun client. We use $this->assertEquals on $method, $uri and $body parameters.
        $mailgun = MockedMailgun::create($this, 'POST', 'domain/messages', [], $fileValidator);

        $builder = $mailgun->MessageBuilder();
        $builder->setFromAddress("bob@example.com");
        $builder->addToRecipient("to1@example.com");
        $builder->addToRecipient("to2@example.com");
        $builder->addCcRecipient("cc1@example.com");
        $builder->addCcRecipient("cc2@example.com");
        $builder->addBccRecipient("bcc1@example.com");
        $builder->addBccRecipient("bcc2@example.com");
        $builder->addCustomParameter('foo', 'bar');
        $builder->addCustomParameter('foo', 'baz');
        $builder->addCampaignId('campaign0');
        $builder->addCampaignId('campaign1');
        $builder->setSubject("Foo");
        $builder->setTextBody("Bar");

        $builder->addAttachment("@./tests/TestAssets/mailgun_icon1.png", 'foo.png');
        $builder->addAttachment("@./tests/TestAssets/mailgun_icon1.png", 'foo.png');
        $builder->addInlineImage("@./tests/TestAssets/mailgun_icon2.png", 'bar.png');
        $builder->addInlineImage("@./tests/TestAssets/mailgun_icon2.png", 'bar.png');

        $mailgun->post("domain/messages", $builder->getMessage(), $builder->getFiles());
    }
}
