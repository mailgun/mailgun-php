<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\RFCValidation;
use Egulias\EmailValidator\Validation\SpoofCheckValidation;
use Mailgun\Exception\InvalidArgumentException;

/**
 * We need to override Webmozart\Assert because we want to throw our own Exception.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Assert extends \Webmozart\Assert\Assert
{
    /**
     * Validates the given email address.
     *
     * @param string $address
     */
    public static function email($address)
    {
        // Validates the given value as a string with a minimum and maximum length.
        self::stringNotEmpty($address, 'Email address must be a non-empty string.');
        self::minLength($address, 3, 'Minimum length for the email address is 3 characters.');
        self::maxLength($address, 512, 'Maximum length for the email address is 512 chatacters.');

        // Provides an initial email validation based on `egulias/EmailValidator` library
        $validator = new EmailValidator();

        if (!$validator->isValid($address, new RFCValidation())) {
            static::reportInvalidArgument(sprintf(
                'Email address `%s` has thrown an error when processing a RFC Validation',
                $address
            ));
        } elseif (!$validator->isValid($address, new DNSCheckValidation())) {
            static::reportInvalidArgument(sprintf(
                'Email address `%s` has thrown an error when processing a DNS Check Validation',
                $address
            ));
        } elseif (!$validator->isValid($address, new SpoofCheckValidation())) {
            static::reportInvalidArgument(sprintf(
                'Email address `%s` has thrown an error when processing a Spoof Check Validation',
                $address
            ));
        }
    }

    /**
     * Overwrites the existing `reportInvalidArgument` method in order to thrown a Mailgun Exception.
     *
     * @param string $message
     */
    protected static function reportInvalidArgument($message)
    {
        throw new InvalidArgumentException($message);
    }
}
