<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Model\PagingProvider;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
trait Pagination
{
    abstract protected function httpGet($path, array $parameters = [], array $requestHeaders = []);

    abstract protected function hydrateResponse(ResponseInterface $response, $className);

    /**
     * @param PagingProvider $response
     *
     * @return PagingProvider|null
     */
    public function nextPage(PagingProvider $response)
    {
        return $this->getPaginationUrl($response->getNextUrl(), get_class($response));
    }

    /**
     * @param PagingProvider $response
     *
     * @return PagingProvider|null
     */
    public function previousPage(PagingProvider $response)
    {
        return $this->getPaginationUrl($response->getPreviousUrl(), get_class($response));
    }

    /**
     * @param PagingProvider $response
     *
     * @return PagingProvider|null
     */
    public function firstPage(PagingProvider $response)
    {
        return $this->getPaginationUrl($response->getFirstUrl(), get_class($response));
    }

    /**
     * @param PagingProvider $response
     *
     * @return PagingProvider|null
     */
    public function lastPage(PagingProvider $response)
    {
        return $this->getPaginationUrl($response->getLastUrl(), get_class($response));
    }

    /**
     * @param string $url
     * @param string $class
     *
     * @return PagingProvider|null
     */
    private function getPaginationUrl($url, $class)
    {
        Assert::stringNotEmpty($class);

        if (empty($url)) {
            return;
        }

        $response = $this->httpGet($url);

        return $this->hydrateResponse($response, $class);
    }
}
