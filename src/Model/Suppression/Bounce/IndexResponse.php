<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Bounce;

use Mailgun\Model\ApiResponse;
use Mailgun\Model\PaginationResponse;
use Mailgun\Model\PagingProvider;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
final class IndexResponse implements ApiResponse, PagingProvider
{
    use PaginationResponse;

    private $items;

    private function __construct(array $items, array $paging)
    {
        $this->items = $items;
        $this->paging = $paging;
    }

    public static function create(array $data): self
    {
        $bounces = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $bounces[] = Bounce::create($item);
            }
        }

        return new self($bounces, $data['paging']);
    }

    /**
     * @return Bounce[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
