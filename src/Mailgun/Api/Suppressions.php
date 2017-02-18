<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Http\Client\HttpClient;
use Mailgun\Deserializer\ResponseDeserializer;
use Mailgun\RequestBuilder;

/**
 * @see https://documentation.mailgun.com/api-suppressions.html
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class Suppressions
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * @var ResponseDeserializer
     */
    private $deserializer;

    /**
     * @param HttpClient           $httpClient
     * @param RequestBuilder       $requestBuilder
     * @param ResponseDeserializer $deserializer
     */
    public function __construct(HttpClient $httpClient, RequestBuilder $requestBuilder, ResponseDeserializer $deserializer)
    {
        $this->httpClient = $httpClient;
        $this->requestBuilder = $requestBuilder;
        $this->deserializer = $deserializer;
    }

    /**
     * @return Suppressions\Bounce
     */
    public function bounces()
    {
        return new Suppressions\Bounce($this->httpClient, $this->requestBuilder, $this->deserializer);
    }

    /**
     * @return Suppressions\Complaint
     */
    public function complaints()
    {
        return new Suppressions\Complaint($this->httpClient, $this->requestBuilder, $this->deserializer);
    }

    /**
     * @return Suppressions\Unsubscribe
     */
    public function unsubscribes()
    {
        return new Suppressions\Unsubscribe($this->httpClient, $this->requestBuilder, $this->deserializer);
    }
}
