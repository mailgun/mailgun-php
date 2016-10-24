<?php

namespace Mailgun;

use Mailgun\Exception\InvalidArgumentException;

/**
 * We need to override Webmozart\Assert because we want to throw our own Exception.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Assert extends \Webmozart\Assert\Assert
{
    protected static function createInvalidArgumentException($message)
    {
        return new InvalidArgumentException($message);
    }
}
