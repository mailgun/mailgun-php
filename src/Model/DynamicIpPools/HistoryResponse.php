<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\DynamicIpPools;

use Mailgun\Model\ApiResponse;
use Mailgun\Model\PaginationResponse;
use Mailgun\Model\PagingProvider;

/**
 * Response for GET /v1/dynamic_pools/history and GET /v1/dynamic_pools/domains/{name}/history.
 */
final class HistoryResponse implements ApiResponse, PagingProvider
{
    use PaginationResponse;

    /**
     * @var HistoryItem[]
     */
    private $items;

    /**
     * @var int
     */
    private $totalItems;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->totalItems = (int) ($data['total_items'] ?? 0);
        $model->paging = array_change_key_case($data['paging'] ?? [], CASE_LOWER);

        $model->items = [];
        foreach ($data['items'] ?? [] as $item) {
            $model->items[] = HistoryItem::create($item);
        }

        return $model;
    }

    /**
     * @return HistoryItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }
}
