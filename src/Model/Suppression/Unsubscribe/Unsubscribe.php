<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Unsubscribe;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class Unsubscribe
{
    private $address;
    private $createdAt;
    private $tags = [];

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->tags = $data['tags'] ?? [];
        $model->address = $data['address'] ?? null;
        $model->createdAt = isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null;

        return $model;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getTags(): array
    {
        return $this->tags;
    }
}
