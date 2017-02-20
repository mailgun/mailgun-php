<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Suppressions\Unsubscribe;

use Mailgun\Resource\Api\PaginationResponse;
use Mailgun\Resource\ApiResponse;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class IndexResponse implements ApiResponse
{
    use PaginationResponse;

    /**
     * @var Unsubscribe[]
     */
    private $items;

    /**
     * @param Unsubscribe[] $items
     * @param array         $paging
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
        $unsubscribes = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $unsubscribes[] = Unsubscribes::create($item);
            }
        }

        return new self($unsubscribes, $data['paging']);
    }

    /**
     * @return Unsubscribe[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
