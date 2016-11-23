<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Mailgun\Exception;

use Mailgun\Exception;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class HttpServerException extends \RuntimeException implements Exception
{
    public static function serverError($httpStatus = 500)
    {
        return new self('An unexpected error occurred at Mailgun\'s servers. Try again later and contact support of the error sill exists.', $httpStatus);
    }

    public static function networkError(\Exception $previous)
    {
        return new self('Mailgun\'s servers was unreachable.', 0, $previous);
    }

    public static function unknownHttpResponseCode($code)
    {
        return new self(sprintf('Unknown HTTP response code ("%d") received from the API server', $code));
    }
}
