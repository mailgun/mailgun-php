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
final class IndexResponse implements ApiResponse
{
    private $totalCount;
    private $items;

    public static function create(array $data): self
    {
        $items = [];

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $items[] = Route::create($item);
            }
        }

        $model = new self();
        $model->items = $items;
        $model->totalCount = (int) ($data['total_count'] ?? count($items));

        return $model;
    }

    private function __construct()
    {
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->items;
    }
}
