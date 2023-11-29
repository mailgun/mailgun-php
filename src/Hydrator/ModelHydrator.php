<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Hydrator;

use Mailgun\Exception\HydrationException;
use Mailgun\Model\ApiResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * Serialize an HTTP response to domain object.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ModelHydrator implements Hydrator
{
    /**
     * @param  class-string      $class
     * @return ResponseInterface
     * @throws \JsonException
     */
    public function hydrate(ResponseInterface $response, string $class)
    {
        $body = $response->getBody()->__toString();
        $contentType = $response->getHeaderLine('Content-Type');

        if (0 !== strpos($contentType, 'application/json') && 0 !== strpos($contentType, 'application/octet-stream')) {
            throw new HydrationException('The ModelHydrator cannot hydrate response with Content-Type: '.$contentType);
        }

        try {
            $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new HydrationException(sprintf('Error (%d) when trying to json_decode response: %s', $exception->getCode(), $exception->getMessage()));
        }

        if (is_subclass_of($class, ApiResponse::class)) {
            $object = call_user_func([$class, 'create'], $data);
        } else {
            $object = new $class($data);
        }

        if (method_exists($object, 'setRawStream')) {
            $object->setRawStream($response->getBody());
        }

        return $object;
    }
}
