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
use Mailgun\Message\MessageBuilder;

class TooManyRecipients extends LimitExceeded implements Exception
{
    public static function create(string $field, int $limit = MessageBuilder::RECIPIENT_COUNT_LIMIT)
    {
        return new self(sprintf('You\'ve exceeded the maximum recipient count (%s) for filed "%s".', $limit, $field));
    }

    public static function whenAutoSendDisabled(int $limit = MessageBuilder::RECIPIENT_COUNT_LIMIT)
    {
        return new self(sprintf('You\'ve exceeded the maximum recipient count (%s) with autosend disabled.', $limit));
    }
}
