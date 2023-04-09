<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Model\PagingProvider;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
trait Pagination
{
    abstract protected function httpGet(string $path, array $parameters = [], array $requestHeaders = []): ResponseInterface;

    /**
     * @param class-string $className
     */
    abstract protected function hydrateResponse(ResponseInterface $response, string $className);

    /**
     * @param  PagingProvider           $response
     * @return PagingProvider|null
     * @throws ClientExceptionInterface
     */
    public function nextPage(PagingProvider $response): ?PagingProvider
    {
        return $this->getPaginationUrl($response->getNextUrl(), get_class($response));
    }

    /**
     * @param  PagingProvider           $response
     * @return PagingProvider|null
     * @throws ClientExceptionInterface
     */
    public function previousPage(PagingProvider $response): ?PagingProvider
    {
        return $this->getPaginationUrl($response->getPreviousUrl(), get_class($response));
    }

    /**
     * @param  PagingProvider      $response
     * @return PagingProvider|null
     */
    public function firstPage(PagingProvider $response): ?PagingProvider
    {
        return $this->getPaginationUrl($response->getFirstUrl(), get_class($response));
    }

    /**
     * @param  PagingProvider           $response
     * @return PagingProvider|null
     * @throws ClientExceptionInterface
     */
    public function lastPage(PagingProvider $response): ?PagingProvider
    {
        return $this->getPaginationUrl($response->getLastUrl(), get_class($response));
    }

    /**
     * @param  string                   $url
     * @param  class-string             $class
     * @return PagingProvider|null
     * @throws ClientExceptionInterface
     */
    private function getPaginationUrl(string $url, string $class): ?PagingProvider
    {
        Assert::stringNotEmpty($class);

        if (empty($url)) {
            return null;
        }

        $response = $this->httpGet($url);

        return $this->hydrateResponse($response, $class);
    }
}
