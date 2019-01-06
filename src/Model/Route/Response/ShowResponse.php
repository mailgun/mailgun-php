<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Route\Response;

use Mailgun\Model\Route\Route;
use Mailgun\Model\ApiResponse;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class ShowResponse implements ApiResponse
{
    /**
     * @var Route|null
     */
    private $route;

    /**
     * {@inheritdoc}
     */
    public static function create(array $data)
    {
        if (isset($data['route'])) {
            return new self(Route::create($data['route']));
        }

        return new self();
    }

    /**
     * ShowResponse constructor.
     *
     * @param Route|null $route
     */
    private function __construct(Route $route = null)
    {
        $this->route = $route;
    }

    /**
     * @return Route|null
     */
    public function getRoute()
    {
        return $this->route;
    }
}
