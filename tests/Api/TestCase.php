<?php

namespace Mailgun\Tests\Api;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Contributors of https://github.com/KnpLabs/php-github-api
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Private Mailgun API key.
     *
     * @var string
     */
    protected $apiPrivKey;

    /**
     * Public Mailgun API key.
     *
     * @var string
     */
    protected $apiPubKey;

    /**
     * Domain used for API testing.
     *
     * @var string
     */
    protected $testDomain;

    public function __construct()
    {
        $this->apiPrivKey = getenv('MAILGUN_PRIV_KEY');
        $this->apiPubKey = getenv('MAILGUN_PUB_KEY');
        $this->testDomain = getenv('MAILGUN_DOMAIN');
    }

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

        $serializer = $this->getMockBuilder('Mailgun\Serializer\ResponseDeserializer')
            ->setMethods(['deserialize'])
            ->getMock();

        return $this->getMockBuilder($this->getApiClass())
            ->setMethods(
                [
                    'get',
                    'post', 'postRaw', 'postMultipart',
                    'deleteHttp', 'deleteMultipart',
                    'put', 'putMultipart',
                ]
            )
            ->setConstructorArgs([$httpClient, $requestClient, $serializer])
            ->getMock();
    }

    protected function getMailgunClient()
    {
        return new \Mailgun\Mailgun($this->apiPrivKey);
    }
}
