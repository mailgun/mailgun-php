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

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class IndexResponse implements ApiResponse
{
    /**
     * @var string[]
     */
    private $items;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * @var string[]
     */
    private $assignableToPools;

    private function __construct()
    {
    }

    public static function create(array $data)
    {
        $model = new self();
        $model->items = $data['items'];
        $model->totalCount = $data['total_count'] ?? 0;
        $model->assignableToPools = $data['assignable_to_pools'];

        return $model;
    }

    /**
     * @return string[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return string[]
     */
    public function getAssignableToPools(): array
    {
        return $this->assignableToPools;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }
}
