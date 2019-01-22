<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Domain;

use Mailgun\Model\ApiResponse;

/**
 * @author Sean Johnson <sean@mailgun.com>
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
                $items[] = Domain::create($item);
            }
        }

        if (isset($data['total_count'])) {
            $count = $data['total_count'];
        } else {
            $count = count($items);
        }

        $model = new self();
        $model->totalCount = $count;
        $model->items = $items;

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
     * @return Domain[]
     */
    public function getDomains(): array
    {
        return $this->items;
    }
}
