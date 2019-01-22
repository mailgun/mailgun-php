<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Stats;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class AllResponseItem
{
    private $id;
    private $event;
    private $totalCount;
    private $tags;
    private $createdAt;

    public static function create(array $data): self
    {
        $model = new self();
        $model->id = $data['id'] ?? null;
        $model->event = $data['event'] ?? null;
        $model->totalCount = (int) ($data['total_count'] ?? 0);
        $model->tags = $data['tags'] ?? [];
        $model->createdAt = isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null;

        return $model;
    }

    private function __construct()
    {
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
}
