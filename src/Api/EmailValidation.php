<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Model\EmailValidation\ParseResponse;
use Mailgun\Model\EmailValidation\ValidateResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @see https://documentation.mailgun.com/en/latest/api-email-validation.html
 *
 * @author David Garcia <me@davidgarcia.cat>
 */
class EmailValidation extends HttpApi
{
    /**
     * Addresses are validated based off defined checks.
     * This operation is only accessible with the private API key and not subject to the daily usage limits.
     * @param  string                             $address             An email address to validate. Maximum: 512 characters.
     * @param  bool                               $mailboxVerification If set to true, a mailbox verification check will be performed
     *                                                                 against the address. The default is False.
     * @param  array                              $requestHeaders
     * @return ValidateResponse|ResponseInterface
     * @throws ClientExceptionInterface           Thrown when we don't catch a Client or Server side Exception
     */
    public function validate(string $address, bool $mailboxVerification = false, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($address);

        $params = [
            'address' => $address,
            'mailbox_verification' => $mailboxVerification,
        ];

        $response = $this->httpGet('/v3/address/private/validate', $params, $requestHeaders);

        return $this->hydrateResponse($response, ValidateResponse::class);
    }

    /**
     * Parses a delimiter-separated list of email addresses into two lists: parsed addresses and unparsable portions.
     * The parsed addresses are a list of addresses that are syntactically valid
     * (and optionally pass DNS and ESP specific grammar checks).
     * The unparsable list is a list of character sequences that could not be parsed
     * (or optionally failed DNS or ESP specific grammar checks).
     * Delimiter characters are comma (,) and semicolon (;).
     * This operation is only accessible with the private API key and not subject to the daily usage limits.
     * @param  string                          $addresses      A delimiter separated list of addresses. Maximum: 8000 characters.
     * @param  bool                            $syntaxOnly     Perform only syntax checks or DNS and ESP specific validation as well.
     *                                                         The default is True.
     * @param  array                           $requestHeaders
     * @return ParseResponse|ResponseInterface
     * @throws ClientExceptionInterface        Thrown when we don't catch a Client or Server side Exception
     */
    public function parse(string $addresses, bool $syntaxOnly = true, array $requestHeaders = [])
    {
        Assert::stringNotEmpty($addresses);
        Assert::maxLength($addresses, 8000);

        $params = [
            'addresses' => $addresses,
            'syntax_only' => $syntaxOnly,
        ];

        $response = $this->httpGet('/v3/address/private/parse', $params, $requestHeaders);

        return $this->hydrateResponse($response, ParseResponse::class);
    }
}
