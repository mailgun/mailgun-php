<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Stats;

use Mailgun\Resource\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class AllResponse implements ApiResponse
{
    /**
     * @var int
     */
    private $totalCount;

    /**
     * @var AllResponseItem[]
     */
    private $items;

    /**
     * @param int               $totalCount
     * @param AllResponseItem[] $items
     */
    private function __construct($totalCount, array $items)
    {
        $this->totalCount = $totalCount;
        $this->items = $items;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        $items = [];
        foreach ($data['items'] as $i) {
            $items[] = AllResponseItem::create($i);
        }

        return new self($data['total_count'], $items);
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @return AllResponseItem[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
