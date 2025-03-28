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
    private int $totalCount;
    private array $items;
    private array $paging;

    public static function create(array $data): self
    {
        $items = [];
        $paging = [];

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

        $paging = $data['paging'] ?? [];

        $model = new self();
        $model->totalCount = $count;
        $model->items = $items;
        $model->paging = $paging;

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * @return int
     */
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

    /**
     * @return array
     */
    public function getPaging(): array
    {
        return $this->paging;
    }

    /**
     * @param array $paging
     * @return void
     */
    public function setPaging(array $paging): void
    {
        $this->paging = $paging;
    }
}
