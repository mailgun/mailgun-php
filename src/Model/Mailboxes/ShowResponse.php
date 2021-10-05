<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Mailboxes;

use Mailgun\Model\ApiResponse;

final class ShowResponse implements ApiResponse
{
    private $totalCount;
    private $items;

    public static function create(array $data): self
    {
        $model = new self();
        $model->totalCount = $data['total_count'] ?? null;
        $model->items = $data['items'] ?? null;

        return $model;
    }

    private function __construct()
    {
    }

    public function getTotalCount(): ?string
    {
        return $this->totalCount;
    }

    public function getItems(): ?string
    {
        return $this->items;
    }
}
