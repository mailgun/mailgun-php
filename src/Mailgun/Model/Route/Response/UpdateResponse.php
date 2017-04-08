<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Route\Response;

use Mailgun\Model\ApiResponse;
use Mailgun\Model\Route\Route;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class UpdateResponse implements ApiResponse
{
    /**
     * @var string|null
     */
    private $message;

    /**
     * @var Route|null
     */
    private $route;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        $message = isset($data['message']) ? $data['message'] : null;
        $route = isset($data['id']) ? Route::create($data) : null;

        return new self($message, $route);
    }

    /**
     * @param string|null $message
     * @param Route|null  $route
     */
    private function __construct($message = null, Route $route = null)
    {
        $this->message = $message;
        $this->route = $route;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return Route|null
     */
    public function getRoute()
    {
        return $this->route;
    }
}
