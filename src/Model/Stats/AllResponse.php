<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Stats;

use Mailgun\Model\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class AllResponse implements ApiResponse
{
    private $totalCount;
    private $items;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $items = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $i) {
                $items[] = AllResponseItem::create($i);
            }
        }

        $model = new self();
        $model->totalCount = (int) ($data['total_count'] ?? count($items));
        $model->items = $items;

        return $model;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return AllResponseItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
