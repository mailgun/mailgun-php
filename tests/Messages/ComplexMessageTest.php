<?PHP

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Mailgun\Tests\Messages;

use Mailgun\Connection\RestClient;
use Mailgun\Tests\Mock\Mailgun;

class mockRestClient extends RestClient
{
    public function send($method, $uri, $body = null, $files = [], array $headers = [])
    {
        $result = new \stdClass();

        $result->method = $method;
        $result->uri = $uri;
        $result->body = $body;
        $result->files = $files;
        $result->headers = $headers;

        return $result;
    }
}

class mockMailgun extends Mailgun
{
    public function __construct(
      $apiKey = null,
      HttpClient $httpClient = null,
      $apiEndpoint = 'api.mailgun.net'
  ) {
        $this->apiKey = $apiKey;
        $this->restClient = new mockRestClient($apiKey, $apiEndpoint, $httpClient);
    }
}

class ComplexMessageTest extends \Mailgun\Tests\MailgunTestCase
{
    private $client;
    private $sampleDomain = 'samples.mailgun.org';

    public function setUp()
    {
        $this->client = new mockMailgun('My-Super-Awesome-API-Key');
    }

    public function testSendComplexMessage()
    {
        $message = [
          'to' => 'test@test.mailgun.org',
          'from' => 'sender@test.mailgun.org',
          'subject' => 'This is my test subject',
          'text' => 'Testing!',
        ];

        $files = [
            'inline' => [
              [
                'remoteName' => 'mailgun_icon1.png',
                'filePath' => 'tests/TestAssets/mailgun_icon1.png',
              ],
              [
                'remoteName' => 'mailgun_icon2.png',
                'filePath' => 'tests/TestAssets/mailgun_icon2.png',
              ],
            ],
        ];

        $result = $this->client->sendMessage('test.mailgun.org', $message, $files);

        $this->assertEquals('POST', $result->method);
        $this->assertEquals('test.mailgun.org/messages', $result->uri);
        $this->assertEquals([], $result->body);

        // Start a counter, make sure all files are asserted
        $testCount = 0;

        foreach ($result->files as $file) {
            if ($file['name'] == 'to') {
                $this->assertEquals($file['contents'], 'test@test.mailgun.org');
                ++$testCount;
            }
            if ($file['name'] == 'from') {
                $this->assertEquals($file['contents'], 'sender@test.mailgun.org');
                ++$testCount;
            }
            if ($file['name'] == 'subject') {
                $this->assertEquals($file['contents'], 'This is my test subject');
                ++$testCount;
            }
            if ($file['name'] == 'text') {
                $this->assertEquals($file['contents'], 'Testing!');
                ++$testCount;
            }
            if ($file['name'] == 'inline[0]') {
                $this->assertEquals($file['filename'], 'mailgun_icon1.png');
                ++$testCount;
            }
            if ($file['name'] == 'inline[1]') {
                $this->assertEquals($file['filename'], 'mailgun_icon2.png');
                ++$testCount;
            }
        }

        // Make sure all "files" are asserted
        $this->assertEquals(count($result->files), $testCount);

        $this->assertEquals([], $result->body);
        $this->assertEquals([], $result->headers);
    }
}
