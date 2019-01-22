<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Complaint;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class Complaint
{
    private $address;
    private $createdAt;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $complaint = new self();
        $complaint->address = $data['address'];
        $complaint->createdAt = new \DateTimeImmutable();

        $complaint->createdAt = isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : new \DateTimeImmutable();

        return $complaint;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
