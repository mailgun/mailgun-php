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

    private function __construct($address)
    {
        $this->address = $address;
        $this->createdAt = new \DateTime();
    }

    public static function create(array $data): self
    {
        $unsubscribe = new self($data['address']);

        if (isset($data['tags'])) {
            $unsubscribe->tags = $data['tags'];
        }
        if (isset($data['created_at'])) {
            $unsubscribe->createdAt = new \DateTime($data['created_at']);
        }

        return $unsubscribe;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getTags(): array
    {
        return $this->tags;
    }
}
