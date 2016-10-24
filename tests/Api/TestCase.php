<?php

namespace Mailgun\Tests\Api;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Contributors of https://github.com/KnpLabs/php-github-api
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    abstract protected function getApiClass();

    protected function getApiMock()
    {
        $httpClient = $this->getMockBuilder('Http\Client\HttpClient')
            ->setMethods(['sendRequest'])
            ->getMock();
        $httpClient
            ->expects($this->any())
            ->method('sendRequest');

        $requestClient = $this->getMockBuilder('Http\Message\MessageFactory')
            ->setMethods(['createRequest', 'createResponse'])
            ->getMock();

        $serializer = $this->getMockBuilder('Mailgun\Serializer\ResponseSerializer')
            ->setMethods(['deserialize'])
            ->getMock();

        return $this->getMockBuilder($this->getApiClass())
            ->setMethods(['get', 'post', 'postRaw', 'delete', 'put'])
            ->setConstructorArgs([$httpClient, $requestClient, $serializer])
            ->getMock();
    }
}
