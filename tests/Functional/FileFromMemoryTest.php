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
            // Get only the attachments so the properties can be converted to a format we like
            $attachments = array_filter($files, function($f) {
                return strpos($f['name'], 'attachment') !== false;
            });

            $attachments = array_map(function($f) {
                return [
                    'name' => $f['name'],
                    'contents' => is_resource($f['contents'])?fread($f['contents'], 50):$f['contents'],
                    'filename' => $f['filename'],
                ];
            }, $attachments);

            $this->assertContains(['name'=>'attachment[0]', 'contents'=>'File content', 'filename' => 'file1.txt'], $attachments);
            $this->assertContains(['name'=>'attachment[1]', 'contents'=>'Contents of a text file', 'filename' => 'text_file.txt'], $attachments); 
        };

        $attachments = [
            ['filename' => 'file1.txt', 'fileContent' => "File content"],
            ['filePath' => "./tests/TestAssets/text_file.txt", 'remoteName' => 'text_file.txt']
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
