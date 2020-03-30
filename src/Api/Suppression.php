<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Api\Suppression\Bounce;
use Mailgun\Api\Suppression\Complaint;
use Mailgun\Api\Suppression\Unsubscribe;
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
     * @var ClientInterface
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

    public function __construct(ClientInterface $httpClient, RequestBuilder $requestBuilder, Hydrator $hydrator)
    {
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
}
