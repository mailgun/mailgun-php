<?php

namespace Mailgun\Tests\Functional;

use Mailgun\Mailgun;

/**
 * A client to be used in tests.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class MockedMailgun extends Mailgun
{
    /**
     * @param MockedRestClient $restClient
     *
     * @internal Use MockedMailgun::create()
     */
    public function __construct(MockedRestClient $restClient)
    {
        $this->apiKey = 'apikey';
        $this->restClient = $restClient;
    }

    public function getMockedRestClient()
    {
        return $this->restClient;
    }

    /**
     * Create a mocked mailgun client with a mocked RestClient.
     *
     * @param \PHPUnit_Framework_TestCase $testCase
     * @param \Closure|string             $methodValidator
     * @param \Closure|string             $uriValidator
     * @param \Closure|mixed              $bodyValidator
     * @param \Closure|array              $filesValidator
     * @param \Closure|array              $headersValidator
     */
    public static function create(
        \PHPUnit_Framework_TestCase $testCase,
        $methodValidator,
        $uriValidator,
        $bodyValidator = null,
        $filesValidator = [],
        $headersValidator = []
    ) {
        if (!$methodValidator instanceof \Closure) {
            $methodValidator = self::createClosure($testCase, $methodValidator);
        }

        if (!$uriValidator instanceof \Closure) {
            $uriValidator = self::createClosure($testCase, $uriValidator);
        }

        if (!$bodyValidator instanceof \Closure) {
            $bodyValidator = self::createClosure($testCase, $bodyValidator);
        }

        if (!$filesValidator instanceof \Closure) {
            $filesValidator = self::createClosure($testCase, $filesValidator);
        }

        if (!$headersValidator instanceof \Closure) {
            $headersValidator = self::createClosure($testCase, $headersValidator);
        }

        return new self(new MockedRestClient($methodValidator, $uriValidator, $bodyValidator, $filesValidator, $headersValidator));
    }

    /**
     * Return a closure.
     *
     * @param \PHPUnit_Framework_TestCase $testCase
     * @param $expectedValue
     *
     * @return \Closure
     */
    private static function createClosure(\PHPUnit_Framework_TestCase $testCase, $expectedValue)
    {
        return function ($value) use ($testCase, $expectedValue) {
            $testCase->assertEquals($expectedValue, $value);
        };
    }
}
