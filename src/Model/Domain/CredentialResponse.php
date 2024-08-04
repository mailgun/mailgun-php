<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Domain;

use Mailgun\Model\ApiResponse;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
final class CredentialResponse implements ApiResponse
{
    private int $totalCount;
    private $items;

    public static function create(array $data): self
    {
        $items = [];
        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $items[] = CredentialResponseItem::create($item);
            }
        }

        if (isset($data['total_count'])) {
            $count = (int) $data['total_count'];
        } else {
            $count = count($items);
        }

        $model = new self();
        $model->totalCount = $count;
        $model->items = $items;

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return CredentialResponseItem[]
     */
    public function getCredentials(): array
    {
        return $this->items;
    }
}
