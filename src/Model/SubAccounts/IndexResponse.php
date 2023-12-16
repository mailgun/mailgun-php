<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\SubAccounts;

use Mailgun\Model\ApiResponse;
use Mailgun\Model\PaginationResponse;
use Mailgun\Model\PagingProvider;

final class IndexResponse implements ApiResponse, PagingProvider
{
    use PaginationResponse;

    /**
     * @var SubAccount[]
     */
    private $items;

    /**
     * @var int
     */
    private $total;

    private function __construct()
    {
    }

    /**
     * @param  array      $data
     * @return static
     * @throws \Exception
     */
    public static function create(array $data): self
    {
        $items = [];
        if ($data['subaccounts']) {
            foreach ($data['subaccounts'] as $subaccount) {
                $items[] = SubAccount::create($subaccount);
            }
        }

        $model = new self();
        $model->items = $items;
        $model->total = (int) ($data['total'] ?? 0);

        return $model;
    }

    /**
     * @return SubAccount[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
}
