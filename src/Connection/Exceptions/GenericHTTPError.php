<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Connection\Exceptions;

use Mailgun\Exception;

/**
 * @deprecated Will be removed in 3.0
 */
class GenericHTTPError extends \Exception implements Exception
{
    protected $httpResponseCode;

    protected $httpResponseBody;

    public function __construct($message = null, $response_code = null, $response_body = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->httpResponseCode = $response_code;
        $this->httpResponseBody = $response_body;
    }

    public function getHttpResponseCode()
    {
        return $this->httpResponseCode;
    }

    public function getHttpResponseBody()
    {
        return $this->httpResponseBody;
    }
}
