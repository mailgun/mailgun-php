<?php

namespace Mailgun\Tests\Functional;

/**
 * Add attachment with file from memory
 *
 * @author Wim Verstuyf <wim.verstuyf@codelicious.be>
 */
class FileFromMemoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAddFileFromMemory()
    {
        $fileValidator = function($files) {
            $this->assertContains(['name'=>'from',    'contents'=>'bob@example.com'], $files);
            $this->assertContains(['name'=>'to',      'contents'=>'alice@example.com'], $files);
            $this->assertContains(['name'=>'subject', 'contents'=>'Foo'], $files);
            $this->assertContains(['name'=>'text',    'contents'=>'Bar'], $files);
            $this->assertContains(['name'=>'attachment[0]', 'contents'=>'First file content', 'filename' => 'file1.txt'], $files);
            $this->assertContains(['name'=>'attachment[1]', 'contents'=>'Second file content', 'filename' => 'file2.txt'], $files);
        };

        $attachments = [
            ['filename' => 'file1.txt', 'fileContent' => 'First file content'],
            ['filename' => 'file2.txt', 'fileContent' => 'Second file content']
        ];

        $mailgun = MockedMailgun::create($this, 'POST', 'domain/messages', [], $fileValidator);

        $mailgun->sendMessage('domain', array(
            'from'    => 'bob@example.com',
            'to'      => 'alice@example.com',
            'subject' => 'Foo',
            'text'    => 'Bar'), array(
            'attachment' => $attachments
            ));
    }
}
