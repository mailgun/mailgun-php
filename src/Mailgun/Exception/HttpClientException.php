<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Exception;

use Mailgun\Exception;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class HttpClientException extends \RuntimeException implements Exception
{
    public static function badRequest()
    {
        return new self('The parameters passed to the API were invalid. Check your inputs!', 400);
    }

    public static function unauthorized()
    {
        return new self('Your credentials are incorrect.', 401);
    }

    public static function requestFailed()
    {
        return new self('Parameters were valid but request failed. Try again.', 402);
    }

    public static function notFound()
    {
        return new self('The endpoint you tried to access does not exist. Check your URL.', 404);
    }
}
