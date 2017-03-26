<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Route\Response;

use Mailgun\Model\Route\Route;
use Mailgun\Model\ApiResponse;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class IndexResponse implements ApiResponse
{
    /**
     * @var int
     */
    private $totalCount;

    /**
     * @var Route[]
     */
    private $items;

    /**
     * {@inheritdoc}
     */
    public static function create(array $data)
    {
        $items = [];

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $items[] = Route::create($item);
            }
        }

        if (isset($data['total_count'])) {
            $count = $data['total_count'];
        } else {
            $count = count($items);
        }

        return new self($count, $items);
    }

    /**
     * @param int     $totalCount
     * @param Route[] $items
     */
    private function __construct($totalCount, array $items)
    {
        $this->totalCount = $totalCount;
        $this->items = $items;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @return Route[]
     */
    public function getRoutes()
    {
        return $this->items;
    }
}
