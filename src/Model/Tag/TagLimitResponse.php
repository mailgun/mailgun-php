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

final class TagLimitResponse implements ApiResponse
{
    private string $id;
    private int $limit;
    private int $count;

    /**
     * @param array $data
     * @return self
     */
    public static function create(array $data): TagLimitResponse
    {
        $item = new self();
        $item->setId($data['id'] ?? '');
        $item->setLimit($data['limit'] ?? 0);
        $item->setCount($data['count'] ?? 0);

        return $item;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return void
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return void
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'limit' => $this->getLimit(),
            'count' => $this->getCount(),
        ];
    }
}
