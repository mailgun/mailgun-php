<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList\Member;

use Mailgun\Model\ApiResponse;
use Mailgun\Model\PaginationResponse;
use Mailgun\Model\PagingProvider;

final class IndexResponse implements ApiResponse, PagingProvider
{
    use PaginationResponse;

    /**
     * @var Member[]
     */
    private $items;

    public static function create(array $data): self
    {
        $items = [];

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $items[] = Member::create($item);
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
     * @return Member[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
