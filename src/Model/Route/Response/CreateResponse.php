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
final class CreateResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var Route
     */
    private $route;

    /**
     * {@inheritdoc}
     */
    public static function create(array $data)
    {
        $message = isset($data['message']) ? $data['message'] : null;
        $route = isset($data['route']) ? Route::create($data['route']) : null;

        return new self($message, $route);
    }

    /**
     * CreateResponse Private Constructor.
     *
     * @param string|null $message
     * @param Route|null  $route
     */
    private function __construct($message = null, Route $route = null)
    {
        $this->message = $message;
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }
}
