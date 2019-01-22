<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList;

use Mailgun\Model\ApiResponse;

final class MailingList implements ApiResponse
{
    private $name;
    private $address;
    private $accessLevel;
    private $description;
    private $membersCount;
    private $createdAt;

    public static function create(array $data): self
    {
        $model = new self();
        $model->name = $data['name'] ?? null;
        $model->address = $data['address'] ?? null;
        $model->accessLevel = $data['access_level'] ?? null;
        $model->description = $data['description'] ?? null;
        $model->membersCount = (int) ($data['members_count'] ?? 0);
        $model->createdAt = isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null;

        return $model;
    }

    private function __construct()
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getAccessLevel(): ?string
    {
        return $this->accessLevel;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getMembersCount(): int
    {
        return $this->membersCount;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
