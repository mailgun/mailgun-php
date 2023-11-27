<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Whitelist;

use Mailgun\Model\ApiResponse;
use Mailgun\Model\PaginationResponse;
use Mailgun\Model\PagingProvider;

/**
 * @author Artem Bondarenko <artem@uartema.com>
 */
final class IndexResponse implements ApiResponse, PagingProvider
{
    use PaginationResponse;

    /**
     * Array to store a list of whitelist items from
     * index response.
     *
     * @var Whitelist[]
     */
    private $items = [];

    /**
     * Store the total number of whitelists items.
     *
     * @var int
     */
    private $totalCount;

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
        $whitelists = [];

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $whitelists[] = Whitelist::create($item);
            }
        }

        $model = new self();
        $model->items = $whitelists;
        $model->paging = $data['paging'];

        return $model;
    }

    /**
     * @return Whitelist[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        if (null === $this->totalCount) {
            $this->totalCount = count($this->items);
        }

        return $this->totalCount;
    }
}
