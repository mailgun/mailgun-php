<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Http\Client\HttpClient;
use Mailgun\Api\Suppression\Bounce;
use Mailgun\Api\Suppression\Complaint;
use Mailgun\Api\Suppression\Unsubscribe;
use Mailgun\Api\Suppression\Whitelist;
use Mailgun\HttpClient\RequestBuilder;
use Mailgun\Hydrator\Hydrator;
use Psr\Http\Client\ClientInterface;

/**
 * @see https://documentation.mailgun.com/api-suppressions.html
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
class Suppression
{
    /**
     * @var ClientInterface|HttpClient
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
     * @param ClientInterface|HttpClient $httpClient     Client used to send the requests
     * @param RequestBuilder             $requestBuilder Builder that creates request based on params
     * @param Hydrator                   $hydrator       Transforms request body into array
     */
    public function __construct($httpClient, RequestBuilder $requestBuilder, Hydrator $hydrator)
    {
        if (!$httpClient instanceof ClientInterface &&
            !$httpClient instanceof HttpClient) {
            throw new \RuntimeException('httpClient must be an instance of
            Psr\Http\Client\ClientInterface or Http\Client\Common\HttpClient');
        }
        $this->httpClient = $httpClient;
        $this->requestBuilder = $requestBuilder;
        $this->hydrator = $hydrator;
    }

    public function bounces(): Bounce
    {
        return new Bounce($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    public function complaints(): Complaint
    {
        return new Complaint($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    public function unsubscribes(): Unsubscribe
    {
        return new Unsubscribe($this->httpClient, $this->requestBuilder, $this->hydrator);
    }

    public function whitelists(): Whitelist
    {
        return new Whitelist($this->httpClient, $this->requestBuilder, $this->hydrator);
    }
}
