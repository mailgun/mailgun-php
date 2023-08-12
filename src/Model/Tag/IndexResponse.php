<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Tag;

use Mailgun\Model\ApiResponse;
use Mailgun\Model\PaginationResponse;
use Mailgun\Model\PagingProvider;

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

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $items = [];
        foreach ($data['items'] as $item) {
            $items[] = Tag::create($item);
        }

        $model = new self();
        $model->items = $items;

        // Fix http urls that is coming from server
        $data['paging'] = array_map(
            function (string $url) {
                return str_replace('http://', 'https://', $url);
            }, $data['paging']
        );

        $model->paging = $data['paging'];

        return $model;
    }

    /**
     * @return Tag[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
