<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun;

use Mailgun\Exception\InvalidArgumentException;

/**
 * We need to override Webmozart\Assert because we want to throw our own Exception.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Assert extends \Webmozart\Assert\Assert
{
    /**
     * @psalm-pure
     *
     * @throws InvalidArgumentException
     *
     * @return never
     */
    protected static function reportInvalidArgument($message): void
    {
        throw new InvalidArgumentException($message);
    }
}
