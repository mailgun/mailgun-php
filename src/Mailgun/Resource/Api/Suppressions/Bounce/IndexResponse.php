<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Suppressions\Bounce;

use Mailgun\Resource\Api\PaginationResponse;
use Mailgun\Resource\ApiResponse;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class IndexResponse implements ApiResponse
{
    use PaginationResponse;

    /**
     * @var Bounce[]
     */
    private $items;

    /**
     * @param Bounce[] $items
     * @param array    $paging
     */
    public function __construct(array $items, array $paging)
    {
        $this->items = $items;
        $this->paging = $paging;
    }

    /**
     * @param array $data
     *
     * @return Bounce[]
     */
    public static function create(array $data)
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
    public function getItems()
    {
        return $this->items;
    }
}
