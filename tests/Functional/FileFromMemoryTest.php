<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Functional;

/**
 * Add attachment with file from memory.
 *
 * @author Wim Verstuyf <wim.verstuyf@codelicious.be>
 */
class FileFromMemoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAddFileFromMemory()
    {
        $fileValidator = function ($files) {
            // Get only the attachments so the properties can be converted to a format we like
            $attachments = array_filter($files, function ($f) {
                return false !== strpos($f['name'], 'attachment');
            });

            // Convert resources to strings
            $attachments = array_map(function ($f) {
                return [
                    'name' => $f['name'],
                    'contents' => fread($f['contents'], 50),
                    'filename' => $f['filename'],
                ];
            }, $attachments);

            $this->assertContains(['name' => 'attachment', 'contents' => 'File content', 'filename' => 'file1.txt'], $attachments);
            $this->assertContains(['name' => 'attachment', 'contents' => 'File content 2', 'filename' => 'file2.txt'], $attachments);
            $this->assertContains(['name' => 'attachment', 'contents' => 'Contents of a text file', 'filename' => 'text_file.txt'], $attachments);
        };

        $attachments = [
            ['filename' => 'file1.txt', 'fileContent' => 'File content'],
            ['filename' => 'file2.txt', 'fileContent' => 'File content 2'],
            ['filePath' => './tests/TestAssets/text_file.txt', 'remoteName' => 'text_file.txt'],
        ];

        $mailgun = MockedMailgun::createMock($this, 'POST', 'domain/messages', [], $fileValidator);

        $mailgun->sendMessage('domain', [
            'from' => 'bob@example.com',
            'to' => 'alice@example.com',
            'subject' => 'Foo',
            'text' => 'Bar', ], [
            'attachment' => $attachments,
            ]);
    }
}
