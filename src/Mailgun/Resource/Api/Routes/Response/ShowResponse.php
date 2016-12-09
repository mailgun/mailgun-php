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
final class ShowResponse implements ApiResponse
{
    /**
     * @var RouteDto|null
     */
    private $route;

    /**
     * {@inheritdoc}
     */
    public static function create(array $data)
    {
        if (isset($data['route'])) {
            return new self(RouteDto::create($data['route']));
        }

        return new self();
    }

    /**
     * ShowResponse constructor.
     *
     * @param RouteDto|null $route
     */
    private function __construct(RouteDto $route = null)
    {
        $this->route = $route;
    }

    /**
     * @return RouteDto|null
     */
    public function getRoute()
    {
        return $this->route;
    }
}
