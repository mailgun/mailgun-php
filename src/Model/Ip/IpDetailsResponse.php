<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Ip;

use Mailgun\Model\ApiResponse;

final class IpDetailsResponse implements ApiResponse
{
    /** @var array[] */
    private array $items;

    private int $totalCount;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->items = $data['items'] ?? [];
        $model->totalCount = $data['total_count'] ?? 0;

        return $model;
    }

    /** @return array[] */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }
}
