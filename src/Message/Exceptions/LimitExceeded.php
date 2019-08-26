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

class LimitExceeded extends \Exception implements Exception
{
    public static function create(string $field, int $limit)
    {
        return new self(sprintf('You\'ve exceeded the maximum (%d) %s for a single message.', $limit, $field));
    }
}
