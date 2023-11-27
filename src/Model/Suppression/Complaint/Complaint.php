<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Complaint;

use DateTimeImmutable;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class Complaint
{
    private $address;
    private $createdAt;

    final private function __construct()
    {
    }

    /**
     * @throws \Exception
     */
    public static function create(array $data): self
    {
        $model = new static();
        $model->address = $data['address'] ?? null;
        $model->createdAt = isset($data['created_at']) ? new DateTimeImmutable($data['created_at']) : null;

        return $model;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }
}
