<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Http\Client\HttpClient;
use Mailgun\Api\Suppression\Bounce;
use Mailgun\Api\Suppression\Complaint;
use Mailgun\Api\Suppression\Unsubscribe;
use Mailgun\Hydrator\Hydrator;
use Mailgun\RequestBuilder;

/**
 * @see https://documentation.mailgun.com/api-suppressions.html
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class Suppression
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
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @param HttpClient     $httpClient
     * @param RequestBuilder $requestBuilder
     * @param Hydrator       $hydrator
     */
    public function __construct(HttpClient $httpClient, RequestBuilder $requestBuilder, Hydrator $hydrator)
    {
        $this->httpClient = $httpClient;
        $this->requestBuilder = $requestBuilder;
        $this->hydrator = $hydrator;
    }

    /**
     * @return Bounce
     */
    public function bounces()
    {
        return new Bounce($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Complaint
     */
    public function complaints()
    {
        return new Complaint($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    /**
     * @return Unsubscribe
     */
    public function unsubscribes()
    {
        return new Unsubscribe($this->httpClient, $this->requestBuilder, $this->hydrator);
    }
}
