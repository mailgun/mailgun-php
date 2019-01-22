<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList;

use Mailgun\Model\PagingProvider;
use Mailgun\Model\PaginationResponse;
use Mailgun\Model\ApiResponse;

final class PagesResponse implements ApiResponse, PagingProvider
{
    use PaginationResponse;

    private $items;

    /**
     * @return self
     */
    public static function create(array $data)
    {
        $items = [];

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $items[] = MailingList::create($item);
            }
        }

        $model = new self();
        $model->items = $items;
        $model->paging = $data['paging'];

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * @return MailingList[]
     */
    public function getLists(): array
    {
        return $this->items;
    }
}
