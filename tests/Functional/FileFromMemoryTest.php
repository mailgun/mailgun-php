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
            $fileChecks = [
                ['name'=>'attachment[0]', 'contents'=>'File content', 'filename' => 'file1.txt'],
                ['name'=>'attachment[1]', 'filename' => 'mailgun_icon1.png']
            ];

            // Make sure that both files exists
            foreach ($fileChecks as $idx => $fileCheck) {
                foreach ($files as $file) {
                    if ($file['name'] === $fileCheck['name'] && 
                        $file['filename'] === $fileCheck['filename'] && 
                        (!isset($fileCheck['contents']) || $fileCheck['contents'] === $file['contents'])) {
                        
                        unset ($fileChecks[$idx]);
                        break;
                    }
                }
            }

            $this->assertEmpty($fileChecks, 'Files could not be found');
        };

        $attachments = [
            ['filename' => 'file1.txt', 'fileContent' => "File content"],
            ['filePath' => "./tests/TestAssets/mailgun_icon1.png", 'remoteName' => 'mailgun_icon1.png']
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
