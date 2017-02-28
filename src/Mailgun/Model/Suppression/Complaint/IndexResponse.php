<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Complaint;

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
     * @var Complaint[]
     */
    private $items;

    /**
     * @param Complaint[] $items
     * @param array       $paging
     */
    private function __construct(array $items, array $paging)
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
        $complaints = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $complaints[] = Complaint::create($item);
            }
        }

        return new self($complaints, $data['paging']);
    }

    /**
     * @return Complaint[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
