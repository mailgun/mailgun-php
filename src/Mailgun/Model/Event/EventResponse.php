<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Event;

use Mailgun\Model\PagingProvider;
use Mailgun\Model\PaginationResponse;
use Mailgun\Model\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class EventResponse implements ApiResponse, PagingProvider
{
    use PaginationResponse;

    /**
     * @var Event[]
     */
    private $items;

    /**
     * @param Event[] $items
     * @param array   $paging
     */
    public function __construct(array $items, array $paging)
    {
        $this->items = $items;
        $this->paging = $paging;
    }

    public static function create(array $data)
    {
        $events = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $events[] = Event::create($item);
            }
        }

        return new self($events, $data['paging']);
    }

    /**
     * @return Event[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
