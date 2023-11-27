<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
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

    private $items;

    private function __construct()
    {
    }

    /**
     * @param  array      $data
     * @return static
     * @throws \Exception
     */
    public static function create(array $data): self
    {
        $complaints = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $complaints[] = Complaint::create($item);
            }
        }

        $model = new self();
        $model->items = $complaints;
        $model->paging = $data['paging'];

        return $model;
    }

    /**
     * @return Complaint[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
