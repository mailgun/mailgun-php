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

    private function __construct($address)
    {
        $this->address = $address;
        $this->createdAt = new \DateTime();
    }

    public static function create(array $data): self
    {
        $complaint = new self($data['address']);

        if (isset($data['created_at'])) {
            $complaint->createdAt = new \DateTime($data['created_at']);
        }

        return $complaint;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
