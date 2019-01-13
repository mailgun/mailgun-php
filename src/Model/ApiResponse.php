<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface ApiResponse
{
    /**
     * Create an API response object from the HTTP response from the API server.
     */
    public static function create(array $data);
}
