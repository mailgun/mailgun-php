<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Hydrator;

use Psr\Http\Message\ResponseInterface;

/**
 * Do not serialize at all. Just return a PSR-7 response.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class NoopHydrator implements Hydrator
{
    /**
     * @param class-string $class
     *
     * @throws \LogicException
     */
    public function hydrate(ResponseInterface $response, string $class)
    {
        throw new \LogicException('The NoopHydrator should never be called');
    }
}
