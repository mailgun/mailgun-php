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
final class CreateResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var RouteDto
     */
    private $route;

    /**
     * {@inheritdoc}
     */
    public static function create(array $data)
    {
        $message = isset($data['message']) ? $data['message'] : null;
        $route = isset($data['route']) ? RouteDto::create($data['route']) : null;

        return new self($message, $route);
    }

    /**
     * CreateResponse Private Constructor.
     *
     * @param string|null   $message
     * @param RouteDto|null $route
     */
    private function __construct($message = null, RouteDto $route = null)
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
     * @return RouteDto
     */
    public function getRoute()
    {
        return $this->route;
    }
}
