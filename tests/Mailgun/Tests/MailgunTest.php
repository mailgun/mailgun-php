<?PHP
namespace Mailgun\Tests\Lists;

use Mailgun\Mailgun;

class MailgunTest extends \Mailgun\Tests\MailgunTestCase
{

    public function testSendMessageMissingRequiredMIMEParametersExceptionGetsFlung()
    {
        $this->setExpectedException("\\Mailgun\\Messages\\Exceptions\\MissingRequiredMIMEParameters");

        $client = new Mailgun();
        $client->sendMessage("test.mailgun.com", "etss", 1);
    }
}

?>