<?php

namespace Mailgun\Tests\Model\EmailValidation;

use Mailgun\Model\EmailValidation\Parts;

class PartsTest extends \PHPUnit_Framework_TestCase
{
    public function testPartsConstructor()
    {
        $data = [
            'display_name' => ' Display name',
            'domain' => 'Domain',
            'local_part' => 'Local Part',
        ];

        $parts = Parts::create($data);

        $this->assertEquals($data['display_name'], $parts->getDisplayName());
        $this->assertEquals($data['domain'], $parts->getDomain());
        $this->assertEquals($data['local_part'], $parts->getLocalPart());
    }
}
