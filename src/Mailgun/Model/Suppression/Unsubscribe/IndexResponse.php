<?php

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

    /**
     * @see Mailgun/Model/Suppression/Unsubscribe/Unsubscribe
     *
     * @param Unsubscribe[] $items
     * @param array         $paging
     */
    private function __construct(array $items, array $paging)
    {
        $this->items = $items;
        $this->paging = $paging;
    }

    /**
     * Allow create the unsubscribe items with paging.
     *
     * @param array $data
     *
     * @return IndexResponse
     */
    public static function create(array $data)
    {
        $unsubscribes = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $unsubscribes[] = Unsubscribe::create($item);
            }
        }

        return new self($unsubscribes, $data['paging']);
    }

    /**
     * Get the Unsusbscribe item models from the response.
     *
     * @see Mailgun/Model/Suppression/Unsubscribe/Unsubscribe
     *
     * @return Unsubscribe[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get the total count of Unsusbscribe in index response.
     *
     * @see Mailgun/Model/Suppression/Unsubscribe/Unsubscribe
     *
     * @return int
     */
    public function getTotalCount()
    {
        if (null === $this->totalCount) {
            $this->totalCount = count($this->items);
        }

        return $this->totalCount;
    }
}
