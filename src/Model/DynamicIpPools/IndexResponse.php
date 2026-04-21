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

/**
 * Response for GET /v3/dynamic_pools.
 * Returns the list of IPs belonging to each of the account's Dynamic IP Pools.
 */
final class IndexResponse implements ApiResponse
{
    /**
     * @var array
     */
    private $items;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->items = $data['items'] ?? [];

        return $model;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
