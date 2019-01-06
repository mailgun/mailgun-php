<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList\Member;

use Mailgun\Model\PagingProvider;
use Mailgun\Model\PaginationResponse;
use Mailgun\Model\ApiResponse;

final class IndexResponse implements ApiResponse, PagingProvider
{
    use PaginationResponse;

    /**
     * @var Member[]
     */
    private $items;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        $items = [];

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $items[] = Member::create($item);
            }
        }

        return new self($items, $data['paging']);
    }

    /**
     * @param Member[] $items
     * @param array    $paging
     */
    private function __construct(array $items, array $paging)
    {
        $this->items = $items;
        $this->paging = $paging;
    }

    /**
     * @return Member[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
