<?php

namespace Mailgun\Tests\Functional;

use Mailgun\Connection\RestClient;

/**
 * A rest client that validate arguments to the send method.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
final class MockedRestClient extends RestClient
{
    /**
     * @var \Closure
     */
    private $methodValidator;

    /**
     * @var \Closure
     */
    private $uriValidator;

    /**
     * @var \Closure
     */
    private $bodyValidator;

    /**
     * @var \Closure
     */
    private $filesValidator;

    /**
     * @var \Closure
     */
    private $headersValidator;

    /**
     * @param \Closure $methodValidator
     * @param \Closure $uriValidator
     * @param \Closure $bodyValidator
     * @param \Closure $filesValidator
     * @param \Closure $headersValidator
     *
     * @internal Do not use this constructor. Use MockedMailgun::create()
     */
    public function __construct(
        \Closure $methodValidator,
        \Closure $uriValidator,
        \Closure $bodyValidator,
        \Closure $filesValidator,
        \Closure $headersValidator
    ) {
        $this->methodValidator = $methodValidator;
        $this->uriValidator = $uriValidator;
        $this->bodyValidator = $bodyValidator;
        $this->filesValidator = $filesValidator;
        $this->headersValidator = $headersValidator;
    }

    /**
     * Override the send function and validate the parameters.
     */
    protected function send($method, $uri, $body = null, $files = [], array $headers = [])
    {
        $f = $this->methodValidator;
        $f($method);

        $f = $this->uriValidator;
        $f($uri);

        $f = $this->bodyValidator;
        $f($body);

        $f = $this->filesValidator;
        $f($files);

        $f = $this->headersValidator;
        $f($headers);
    }
}
