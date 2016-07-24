<?PHP

namespace Mailgun\Tests\Messages;

use Mailgun\Tests\Mock\Mailgun;

class MessageBuilderTest extends \Mailgun\Tests\MailgunTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = new Mailgun();
    }

    public function testBlankInstantiation()
    {
        $message = $this->client->MessageBuilder();
        $this->assertTrue(is_array($message->getMessage()));
    }

    public function testCountersSetToZero()
    {
        $message = $this->client->MessageBuilder();

        $reflectionClass = new \ReflectionClass(get_class($message));
        $property = $reflectionClass->getProperty('counters');
        $property->setAccessible(true);
        $propertyValue = $property->getValue($message);
        $this->assertEquals(0, $propertyValue['recipients']['to']);
        $this->assertEquals(0, $propertyValue['recipients']['cc']);
        $this->assertEquals(0, $propertyValue['recipients']['bcc']);
        $this->assertEquals(0, $propertyValue['attributes']['attachment']);
        $this->assertEquals(0, $propertyValue['attributes']['campaign_id']);
        $this->assertEquals(0, $propertyValue['attributes']['custom_option']);
        $this->assertEquals(0, $propertyValue['attributes']['tag']);
    }

    public function testAddToRecipient()
    {
        $message = $this->client->MessageBuilder();
        $message->addToRecipient('test@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);
        $messageObj = $message->getMessage();
        $this->assertEquals(['to' => ["'Test User' <test@samples.mailgun.org>"]], $messageObj);
    }

    public function testAddCcRecipient()
    {
        $message = $this->client->MessageBuilder();
        $message->addCcRecipient('test@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);
        $messageObj = $message->getMessage();
        $this->assertEquals(['cc' => ["'Test User' <test@samples.mailgun.org>"]], $messageObj);
    }

    public function testAddBccRecipient()
    {
        $message = $this->client->MessageBuilder();
        $message->addBccRecipient('test@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);
        $messageObj = $message->getMessage();
        $this->assertEquals(['bcc' => ["'Test User' <test@samples.mailgun.org>"]], $messageObj);
    }

    public function testToRecipientCount()
    {
        $message = $this->client->MessageBuilder();
        $message->addToRecipient('test-user@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);

        $reflectionClass = new \ReflectionClass(get_class($message));
        $property = $reflectionClass->getProperty('counters');
        $property->setAccessible(true);
        $array = $property->getValue($message);
        $this->assertEquals(1, $array['recipients']['to']);
    }

    public function testCcRecipientCount()
    {
        $message = $this->client->MessageBuilder();
        $message->addCcRecipient('test-user@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);

        $reflectionClass = new \ReflectionClass(get_class($message));
        $property = $reflectionClass->getProperty('counters');
        $property->setAccessible(true);
        $array = $property->getValue($message);
        $this->assertEquals(1, $array['recipients']['cc']);
    }

    public function testBccRecipientCount()
    {
        $message = $this->client->MessageBuilder();
        $message->addBccRecipient('test-user@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);

        $reflectionClass = new \ReflectionClass(get_class($message));
        $property = $reflectionClass->getProperty('counters');
        $property->setAccessible(true);
        $array = $property->getValue($message);
        $this->assertEquals(1, $array['recipients']['bcc']);
    }

    public function testSetFromAddress()
    {
        $message = $this->client->MessageBuilder();
        $message->setFromAddress('test@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);
        $messageObj = $message->getMessage();
        $this->assertEquals(['from' => ["'Test User' <test@samples.mailgun.org>"]], $messageObj);
    }

    public function testSetReplyTo()
    {
        $message = $this->client->MessageBuilder();
        $message->setReplyToAddress('test@samples.mailgun.org', ['first' => 'Test', 'last' => 'User']);
        $messageObj = $message->getMessage();
        $this->assertEquals(['h:reply-to' => "'Test User' <test@samples.mailgun.org>"], $messageObj);
    }

    public function testSetSubject()
    {
        $message = $this->client->MessageBuilder();
        $message->setSubject('Test Subject');
        $messageObj = $message->getMessage();
        $this->assertEquals(['subject' => 'Test Subject'], $messageObj);
    }

    public function testAddCustomHeader()
    {
        $message = $this->client->MessageBuilder();
        $message->addCustomHeader('My-Header', '123');
        $messageObj = $message->getMessage();
        $this->assertEquals(['h:My-Header' => ['123']], $messageObj);
    }

    public function testSetTextBody()
    {
        $message = $this->client->MessageBuilder();
        $message->setTextBody('This is the text body!');
        $messageObj = $message->getMessage();
        $this->assertEquals(['text' => 'This is the text body!'], $messageObj);
    }

    public function testSetHtmlBody()
    {
        $message = $this->client->MessageBuilder();
        $message->setHtmlBody('<html><body>This is an awesome email</body></html>');
        $messageObj = $message->getMessage();
        $this->assertEquals(['html' => '<html><body>This is an awesome email</body></html>'], $messageObj);
    }

    public function testAddAttachments()
    {
        $message = $this->client->MessageBuilder();
        $message->addAttachment('@../TestAssets/mailgun_icon.png');
        $message->addAttachment('@../TestAssets/rackspace_logo.png');
        $messageObj = $message->getFiles();
        $this->assertEquals(
            [
                [
                    'filePath'   => '@../TestAssets/mailgun_icon.png',
                    'remoteName' => null,
                ],
                [
                    'filePath'   => '@../TestAssets/rackspace_logo.png',
                    'remoteName' => null,
                ],
            ],
            $messageObj['attachment']
        );
    }

    public function testAddInlineImages()
    {
        $message = $this->client->MessageBuilder();
        $message->addInlineImage('@../TestAssets/mailgun_icon.png');
        $message->addInlineImage('@../TestAssets/rackspace_logo.png');
        $messageObj = $message->getFiles();
        $this->assertEquals(
            [
                [
                    'filePath'   => '@../TestAssets/mailgun_icon.png',
                    'remoteName' => null,
                ],
                [
                    'filePath'   => '@../TestAssets/rackspace_logo.png',
                    'remoteName' => null,
                ],
            ],
            $messageObj['inline']
        );
    }

    public function testAddAttachmentsPostName()
    {
        $message = $this->client->MessageBuilder();
        $message->addAttachment('@../TestAssets/mailgun_icon.png', 'mg_icon.png');
        $message->addAttachment('@../TestAssets/rackspace_logo.png', 'rs_logo.png');
        $messageObj = $message->getFiles();
        $this->assertEquals(
            [
                [
                    'filePath'   => '@../TestAssets/mailgun_icon.png',
                    'remoteName' => 'mg_icon.png',
                ],
                [
                    'filePath'   => '@../TestAssets/rackspace_logo.png',
                    'remoteName' => 'rs_logo.png',
                ],
            ],
            $messageObj['attachment']
        );
    }

    public function testAddInlineImagePostName()
    {
        $message = $this->client->MessageBuilder();
        $message->addInlineImage('@../TestAssets/mailgun_icon.png', 'mg_icon.png');
        $message->addInlineImage('@../TestAssets/rackspace_logo.png', 'rs_logo.png');
        $messageObj = $message->getFiles();
        $this->assertEquals(
            [
                [
                    'filePath'   => '@../TestAssets/mailgun_icon.png',
                    'remoteName' => 'mg_icon.png',
                ],
                [
                    'filePath'   => '@../TestAssets/rackspace_logo.png',
                    'remoteName' => 'rs_logo.png',
                ],
            ],
            $messageObj['inline']
        );
    }

    public function testsetTestMode()
    {
        $message = $this->client->MessageBuilder();
        $message->setTestMode(true);
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:testmode' => 'yes'], $messageObj);
        $message->setTestMode(false);
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:testmode' => 'no'], $messageObj);
        $message->setTestMode('yes');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:testmode' => 'yes'], $messageObj);
        $message->setTestMode('no');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:testmode' => 'no'], $messageObj);
    }

    public function addCampaignId()
    {
        $message = $this->client->MessageBuilder();
        $message->addCampaignId('ABC123');
        $message->addCampaignId('XYZ987');
        $message->addCampaignId('TUV456');
        $message->addCampaignId('NONO123');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:campaign' => ['ABC123', 'XYZ987', 'TUV456']], $messageObj);
    }

    public function testSetDkim()
    {
        $message = $this->client->MessageBuilder();
        $message->setDkim(true);
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:dkim' => 'yes'], $messageObj);
        $message->setDkim(false);
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:dkim' => 'no'], $messageObj);
        $message->setDkim('yes');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:dkim' => 'yes'], $messageObj);
        $message->setDkim('no');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:dkim' => 'no'], $messageObj);
    }

    public function testSetClickTracking()
    {
        $message = $this->client->MessageBuilder();
        $message->setClickTracking(true);
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:tracking-clicks' => 'yes'], $messageObj);
        $message->setClickTracking(false);
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:tracking-clicks' => 'no'], $messageObj);
        $message->setClickTracking('yes');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:tracking-clicks' => 'yes'], $messageObj);
        $message->setClickTracking('no');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:tracking-clicks' => 'no'], $messageObj);
    }

    public function testSetOpenTracking()
    {
        $message = $this->client->MessageBuilder();
        $message->setOpenTracking(true);
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:tracking-opens' => 'yes'], $messageObj);
        $message->setOpenTracking(false);
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:tracking-opens' => 'no'], $messageObj);
        $message->setOpenTracking('yes');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:tracking-opens' => 'yes'], $messageObj);
        $message->setOpenTracking('no');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:tracking-opens' => 'no'], $messageObj);
    }

    public function testSetDeliveryTime()
    {
        $message = $this->client->MessageBuilder();
        $message->setDeliveryTime('January 15, 2014 8:00AM', 'CST');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:deliverytime' => 'Wed, 15 Jan 2014 08:00:00 -0600'], $messageObj);
        $message->setDeliveryTime('January 15, 2014 8:00AM', 'UTC');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:deliverytime' => 'Wed, 15 Jan 2014 08:00:00 +0000'], $messageObj);
        $message->setDeliveryTime('January 15, 2014 8:00AM');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:deliverytime' => 'Wed, 15 Jan 2014 08:00:00 +0000'], $messageObj);
        $message->setDeliveryTime('1/15/2014 13:50:01', 'CDT');
        $messageObj = $message->getMessage();
        $this->assertEquals(['o:deliverytime' => 'Wed, 15 Jan 2014 13:50:01 -0600'], $messageObj);
        // https://github.com/mailgun/mailgun-php/pull/42
        // https://github.com/mailgun/mailgun-php/issues/43
        //$message->setDeliveryTime("first saturday of July 2013 8:00AM", "CDT");
        //$messageObj = $message->getMessage();
        //$this->assertEquals(array("o:deliverytime" => "Sat, 06 Jul 2013 08:00:00 -0500"), $messageObj);
    }

    public function testAddCustomData()
    {
        $message = $this->client->MessageBuilder();
        $message->addCustomData('My-Super-Awesome-Data', ['What' => 'Mailgun Rocks!']);
        $messageObj = $message->getMessage();
        $this->assertEquals(['v:My-Super-Awesome-Data' => '{"What":"Mailgun Rocks!"}'], $messageObj);
    }

    public function testAddCustomParameter()
    {
        $message = $this->client->MessageBuilder();
        $message->addCustomParameter('my-option', 'yes');
        $message->addCustomParameter('o:my-other-option', 'no');
        $messageObj = $message->getMessage();
        $this->assertEquals(['my-option' => ['yes'], 'o:my-other-option' => ['no']], $messageObj);
    }

    public function testSetMessage()
    {
        $message = [1, 2, 3, 4, 5];
        $messageBuilder = $this->client->MessageBuilder();
        $messageBuilder->setMessage($message);

        $this->assertEquals($message, $messageBuilder->getMessage());
    }
}
