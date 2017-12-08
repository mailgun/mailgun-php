<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Exception\InvalidArgumentException;

/**
 * @link https://documentation.mailgun.com/en/latest/api-email-validation.html
 *
 * @author David Garcia <me@davidgarcia.cat>
 */
class EmailValidation
{
    /**
     * Given an arbitrary address, validates address based off defined checks.
     *
     * @param string     $address             An email address to validate. Maximum: 512 characters.
     * @param bool|false $mailboxVerification If set to true, a mailbox verification check will be performed
     *                                        against the address. The default is False.
     *
     * @throws InvalidArgumentException Thrown when validation returns an error
     */
    public function publicValidate($address, $mailboxVerification = false)
    {
        // Validates the email address.
        Assert::email($address);

        // Validates the mailbox verification.
        Assert::boolean($mailboxVerification);
    }

    /**
     * Parses a delimiter-separated list of email addresses into two lists: parsed addresses and unparsable portions.
     *
     * The parsed addresses are a list of addresses that are syntactically valid
     * (and optionally pass DNS and ESP specific grammar checks).
     *
     * The unparsable list is a list of character sequences that could not be parsed
     * (or optionally failed DNS or ESP specific grammar checks).
     *
     * Delimiter characters are comma (,) and semicolon (;).
     *
     * @param string     $addresses  A delimiter separated list of addresses. Maximum: 8000 characters.
     * @param bool|false $syntaxOnly Perform only syntax checks or DNS and ESP specific validation as well.
     *                               The default is True.
     *
     * @throws InvalidArgumentException Thrown when validation returns an error
     */
    public function publicParse($addresses, $syntaxOnly = true)
    {
        // Validates the email addresses.
        Assert::stringNotEmpty($addresses);
        Assert::minLength($addresses, 3);
        Assert::maxLength($addresses, 8000);

        $arrayOfAddresses = preg_split('/;|,/', $addresses);

        foreach ($arrayOfAddresses as $singleAddress) {
            // Validates the email address.
            Assert::email($singleAddress);
        }

        // Validates the Syntax Only verification.
        Assert::boolean($syntaxOnly);
    }

    /**
     * Addresses are validated based off defined checks.
     *
     * This operation is only accessible with the private API key and not subject to the daily usage limits.
     *
     * @param string     $address             An email address to validate. Maximum: 512 characters.
     * @param bool|false $mailboxVerification If set to true, a mailbox verification check will be performed
     *                                        against the address. The default is False.
     *
     * @throws InvalidArgumentException Thrown when validation returns an error
     */
    public function privateValidate($address, $mailboxVerification = false)
    {
        // Validates the email address.
        Assert::email($address);

        // Validates the mailbox verification.
        Assert::boolean($mailboxVerification);
    }

    /**
     * Parses a delimiter-separated list of email addresses into two lists: parsed addresses and unparsable portions.
     *
     * The parsed addresses are a list of addresses that are syntactically valid
     * (and optionally pass DNS and ESP specific grammar checks).
     *
     * The unparsable list is a list of character sequences that could not be parsed
     * (or optionally failed DNS or ESP specific grammar checks).
     *
     * Delimiter characters are comma (,) and semicolon (;).
     *
     * This operation is only accessible with the private API key and not subject to the daily usage limits.
     *
     * @param string     $addresses  A delimiter separated list of addresses. Maximum: 8000 characters.
     * @param bool|false $syntaxOnly Perform only syntax checks or DNS and ESP specific validation as well.
     *                               The default is True.
     *
     * @throws InvalidArgumentException Thrown when validation returns an error
     */
    public function privateParse($addresses, $syntaxOnly = true)
    {
        // Validates the email addresses.
        Assert::stringNotEmpty($addresses);
        Assert::minLength($addresses, 3);
        Assert::maxLength($addresses, 8000);

        $arrayOfAddresses = preg_split('/;|,/', $addresses);

        foreach ($arrayOfAddresses as $singleAddress) {
            // Validates the email address.
            Assert::email($singleAddress);
        }

        // Validates the Syntax Only verification.
        Assert::boolean($syntaxOnly);
    }
}
