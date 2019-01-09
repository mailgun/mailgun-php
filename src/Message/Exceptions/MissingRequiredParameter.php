<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Message\Exceptions;

use Mailgun\Exception;

class MissingRequiredParameter extends \Exception implements Exception
{
    public static function create(string $parameter, string $message = null)
    {
        if (null === $message) {
            $message = 'The parameters passed to the API were invalid. Please specify "%s".';
        }

        return new self(sprintf($message, $parameter));
    }
}
