<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Tag;

use Mailgun\Resource\Api\PaginationResponse;
use Mailgun\Resource\Api\PagingProvider;
use Mailgun\Resource\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class IndexResponse implements ApiResponse, PagingProvider
{
    use PaginationResponse;

    /**
     * @var Tag[]
     */
    private $items;

    /**
     * @param Tag[] $items
     * @param array $paging
     */
    public function __construct(array $items, array $paging)
    {
        $this->items = $items;
        $this->paging = $paging;
    }

    /**
     * @param array $data
     *
     * @return IndexResponse
     */
    public static function create(array $data)
    {
        $items = [];
        foreach ($data['items'] as $item) {
            $items[] = Tag::create($item);
        }

        return new self($items, $data['paging']);
    }

    /**
     * @return Tag[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
