<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Unsubscribe;

use Mailgun\Model\ApiResponse;
use Mailgun\Model\PaginationResponse;
use Mailgun\Model\PagingProvider;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
final class IndexResponse implements ApiResponse, PagingProvider
{
    use PaginationResponse;

    /**
     * Array to store a list of Unsubscribe items from
     * index response.
     *
     * @see Mailgun/Model/Suppression/Unsubscribe/Unsubscribe
     *
     * @var Unsubscribe[]
     */
    private $items = [];

    /**
     * Store the total number of Unsubscribe items.
     *
     * @see Mailgun/Model/Suppression/Unsubscribe/Unsubscribe
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
        $unsubscribes = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $unsubscribes[] = Unsubscribe::create($item);
            }
        }

        $model = new self();
        $model->items = $unsubscribes;
        $model->paging = $data['paging'];

        return $model;
    }

    /**
     * @return Unsubscribe[]
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
