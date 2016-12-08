<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Routes\Response;

use Mailgun\Resource\Api\Routes\Dto\RouteDto;
use Mailgun\Resource\ApiResponse;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
class ShowResponse implements ApiResponse
{
    /**
     * Create an API response object from the HTTP response from the API server.
     *
     * @param array $data
     *
     * @return RouteDto
     */
    public static function create(array $data)
    {
        if (isset($data['route'])) {
            return RouteDto::create($data['route']);
        }

        return RouteDto::create([]);
    }
}
