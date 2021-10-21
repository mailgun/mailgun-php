<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Message;

use Mailgun\Message\MessageBuilder;
use Mailgun\Tests\MailgunTestCase;
use Nyholm\NSA;

class MessageBuilderTest extends MailgunTestCase
{
    /**
     * @var MessageBuilder
     */
    private $messageBuilder;

    public function setUp(): void
    {
        $this->messageBuilder = new MessageBuilder();
    }

    public function testBlankInstantiation()
    {
        $message = $this->messageBuilder->getMessage();
        $this->assertTrue(is_array($message));
    }

    public function testCountersSetToZero()
    {
        $counters = NSA::getProperty($this->messageBuilder, 'counters');
        $this->assertEquals(0, $counters['recipients']['to']);
        $this->assertEquals(0, $counters['recipients']['cc']);
        $this->assertEquals(0, $counters['recipients']['bcc']);
        $this->assertEquals(0, $counters['attributes']['attachment']);
        $this->assertEquals(0, $counters['attributes']['campaign_id']);
        $this->assertEquals(0, $counters['attributes']['custom_option']);
        $this->assertEquals(0, $counters['attributes']['tag']);
    }

    public function testAddToRecipient()
    {
        $this->messageBuilder->addToRecipient('test@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['to' => ['"Test User" <test@samples.mailgun.org>']], $message);
    }

    public function testAddCcRecipient()
    {
        $this->messageBuilder->addCcRecipient('test@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['cc' => ['"Test User" <test@samples.mailgun.org>']], $message);
    }

    public function testAddBccRecipient()
    {
        $this->messageBuilder->addBccRecipient('test@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['bcc' => ['"Test User" <test@samples.mailgun.org>']], $message);
    }

    public function testToRecipientCount()
    {
        $this->messageBuilder->addToRecipient('test-user@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);

        $array = NSA::getProperty($this->messageBuilder, 'counters');
        $this->assertEquals(1, $array['recipients']['to']);
    }

    public function testCcRecipientCount()
    {
        $this->messageBuilder->addCcRecipient('test-user@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);

        $array = NSA::getProperty($this->messageBuilder, 'counters');
        $this->assertEquals(1, $array['recipients']['cc']);
    }

    public function testBccRecipientCount()
    {
        $this->messageBuilder->addBccRecipient('test-user@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);

        $array = NSA::getProperty($this->messageBuilder, 'counters');
        $this->assertEquals(1, $array['recipients']['bcc']);
    }

    public function testSetFromAddress()
    {
        $this->messageBuilder->setFromAddress('test@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['from' => ['"Test User" <test@samples.mailgun.org>']], $message);
    }

    public function testSetReplyTo()
    {
        $this->messageBuilder->setReplyToAddress('overwritten@samples.mailgun.org');
        $this->messageBuilder->setReplyToAddress('test@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['h:reply-to' => '"Test User" <test@samples.mailgun.org>'], $message);
    }

    public function testSetSubject()
    {
        $this->messageBuilder->setSubject('Test Subject');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['subject' => 'Test Subject'], $message);
    }

    public function testAddCustomHeader()
    {
        $this->messageBuilder->addCustomHeader('My-Header', '123');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['h:My-Header' => '123'], $message);
    }

    public function testAddMultipleCustomHeader()
    {
        $this->messageBuilder->addCustomHeader('My-Header', '123');
        $this->messageBuilder->addCustomHeader('My-Header', '456');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['h:My-Header' => ['123', '456']], $message);
    }

    public function testSetTextBody()
    {
        $this->messageBuilder->setTextBody('This is the text body!');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['text' => 'This is the text body!'], $message);
    }

    public function testSetHtmlBody()
    {
        $this->messageBuilder->setHtmlBody('<html><body>This is an awesome email</body></html>');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['html' => '<html><body>This is an awesome email</body></html>'], $message);
    }

    public function testAddAttachments()
    {
        $this->messageBuilder->addAttachment('@../TestAssets/mailgun_icon.png');
        $this->messageBuilder->addAttachment('@../TestAssets/rackspace_logo.png');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(
            [
                [
                    'filePath' => '@../TestAssets/mailgun_icon.png',
                    'filename' => null,
                ],
                [
                    'filePath' => '@../TestAssets/rackspace_logo.png',
                    'filename' => null,
                ],
            ],
            $message['attachment']
        );
    }

    public function testAddStringAttachment()
    {
        $this->messageBuilder->addStringAttachment('12345');
        $this->messageBuilder->addStringAttachment('test');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(
            [
                [
                    'fileContent' => '12345',
                    'filename' => null,
                ],
                [
                    'fileContent' => 'test',
                    'filename' => null,
                ],
            ],
            $message['attachment']
        );
    }

    public function testAddInlineImages()
    {
        $this->messageBuilder->addInlineImage('@../TestAssets/mailgun_icon.png');
        $this->messageBuilder->addInlineImage('@../TestAssets/rackspace_logo.png');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(
            [
                [
                    'filePath' => '@../TestAssets/mailgun_icon.png',
                    'filename' => null,
                ],
                [
                    'filePath' => '@../TestAssets/rackspace_logo.png',
                    'filename' => null,
                ],
            ],
            $message['inline']
        );
    }

    public function testAddAttachmentsPostName()
    {
        $this->messageBuilder->addAttachment('@../TestAssets/mailgun_icon.png', 'mg_icon.png');
        $this->messageBuilder->addAttachment('@../TestAssets/rackspace_logo.png', 'rs_logo.png');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(
            [
                [
                    'filePath' => '@../TestAssets/mailgun_icon.png',
                    'filename' => 'mg_icon.png',
                ],
                [
                    'filePath' => '@../TestAssets/rackspace_logo.png',
                    'filename' => 'rs_logo.png',
                ],
            ],
            $message['attachment']
        );
    }

    public function testAddInlineImagePostName()
    {
        $this->messageBuilder->addInlineImage('@../TestAssets/mailgun_icon.png', 'mg_icon.png');
        $this->messageBuilder->addInlineImage('@../TestAssets/rackspace_logo.png', 'rs_logo.png');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(
            [
                [
                    'filePath' => '@../TestAssets/mailgun_icon.png',
                    'filename' => 'mg_icon.png',
                ],
                [
                    'filePath' => '@../TestAssets/rackspace_logo.png',
                    'filename' => 'rs_logo.png',
                ],
            ],
            $message['inline']
        );
    }

    public function testSetTestMode()
    {
        $this->messageBuilder->setTestMode(true);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:testmode' => 'yes'], $message);

        $this->messageBuilder->setTestMode(false);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:testmode' => 'no'], $message);
    }

    public function testAddCampaignId()
    {
        $this->messageBuilder->addCampaignId('ABC123');
        $this->messageBuilder->addCampaignId('XYZ987');
        $this->messageBuilder->addCampaignId('TUV456');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:campaign' => ['ABC123', 'XYZ987', 'TUV456']], $message);
    }

    public function testSetDkim()
    {
        $this->messageBuilder->setDkim(true);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:dkim' => 'yes'], $message);

        $this->messageBuilder->setDkim(false);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:dkim' => 'no'], $message);
    }

    public function testSetClickTracking()
    {
        $this->messageBuilder->setClickTracking(true);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:tracking-clicks' => 'yes'], $message);

        $this->messageBuilder->setClickTracking(false);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:tracking-clicks' => 'no'], $message);

        $this->messageBuilder->setClickTracking(false, true);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:tracking-clicks' => 'no'], $message);

        $this->messageBuilder->setClickTracking(true, true);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:tracking-clicks' => 'htmlonly'], $message);
    }

    public function testSetOpenTracking()
    {
        $this->messageBuilder->setOpenTracking(true);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:tracking-opens' => 'yes'], $message);

        $this->messageBuilder->setOpenTracking(false);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:tracking-opens' => 'no'], $message);
    }

    public function testSetDeliveryTime()
    {
        $this->messageBuilder->setDeliveryTime('January 15, 2014 8:00AM', 'CST');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:deliverytime' => 'Wed, 15 Jan 2014 08:00:00 -0600'], $message);

        $this->messageBuilder->setDeliveryTime('January 15, 2014 8:00AM', 'UTC');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:deliverytime' => 'Wed, 15 Jan 2014 08:00:00 +0000'], $message);

        $this->messageBuilder->setDeliveryTime('January 15, 2014 8:00AM');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:deliverytime' => 'Wed, 15 Jan 2014 08:00:00 +0000'], $message);

        $this->messageBuilder->setDeliveryTime('1/15/2014 13:50:01', 'CDT');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['o:deliverytime' => 'Wed, 15 Jan 2014 13:50:01 -0600'], $message);
    }

    public function testAddCustomData()
    {
        $this->messageBuilder->addCustomData('My-Super-Awesome-Data', ['What' => 'Mailgun Rocks!']);
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['v:My-Super-Awesome-Data' => '{"What":"Mailgun Rocks!"}'], $message);
    }

    public function testAddCustomParameter()
    {
        $this->messageBuilder->addCustomParameter('my-option', 'yes');
        $this->messageBuilder->addCustomParameter('o:my-other-option', 'no');
        $message = $this->messageBuilder->getMessage();
        $this->assertEquals(['my-option' => ['yes'], 'o:my-other-option' => ['no']], $message);
    }

    public function testSetMessage()
    {
        $message = [1, 2, 3, 4, 5];
        $this->messageBuilder->setMessage($message);

        $this->assertEquals($message, $this->messageBuilder->getMessage());
    }
}
