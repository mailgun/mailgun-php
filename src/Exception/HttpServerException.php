<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Exception;

use Mailgun\Exception;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class HttpServerException extends \RuntimeException implements Exception
{
    /**
     * @param  int                 $httpStatus
     * @return HttpServerException
     */
    public static function serverError(int $httpStatus = 500): HttpServerException
    {
        return new self('An unexpected error occurred at Mailgun\'s servers. Try again later and contact support if the error still exists.', $httpStatus);
    }

    /**
     * @param  \Throwable          $previous
     * @return HttpServerException
     */
    public static function networkError(\Throwable $previous): HttpServerException
    {
        return new self('Mailgun\'s servers are currently unreachable.', 0, $previous);
    }

    /**
     * @param  int                 $code
     * @return HttpServerException
     */
    public static function unknownHttpResponseCode(int $code): HttpServerException
    {
        return new self(sprintf('Unknown HTTP response code ("%d") received from the API server', $code));
    }
}
