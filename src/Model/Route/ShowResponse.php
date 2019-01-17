<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Route;

use Mailgun\Model\ApiResponse;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class ShowResponse implements ApiResponse
{
    private $route;

    public static function create(array $data): self
    {
        $model = new self();
        $model->route = isset($data['route']) ? Route::create($data['route']) : null;

        return $model;
    }

    private function __construct()
    {
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }
}
