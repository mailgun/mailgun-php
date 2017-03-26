<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Hydrator;

use Mailgun\Exception\HydrationException;
use Psr\Http\Message\ResponseInterface;

/**
 * Serialize an HTTP response to array.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ArrayHydrator implements Hydrator
{
    /**
     * @param ResponseInterface $response
     * @param string            $class
     *
     * @return array
     */
    public function hydrate(ResponseInterface $response, $class)
    {
        $body = $response->getBody()->__toString();
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') !== 0) {
            throw new HydrationException('The ArrayHydrator cannot hydrate response with Content-Type:'.$response->getHeaderLine('Content-Type'));
        }

        $content = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new HydrationException(sprintf('Error (%d) when trying to json_decode response', json_last_error()));
        }

        return $content;
    }
}
