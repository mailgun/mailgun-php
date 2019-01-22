<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Bounce;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class Bounce
{
    private $address;
    private $code;
    private $error;
    private $createdAt;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $bounce = new self();
        $bounce->address = $data['address'];
        $bounce->createdAt = new \DateTimeImmutable();

        if (isset($data['code'])) {
            $bounce->code = $data['code'];
        }
        if (isset($data['error'])) {
            $bounce->error = $data['error'];
        }
        if (isset($data['created_at'])) {
            $bounce->createdAt = new \DateTimeImmutable($data['created_at']);
        }

        return $bounce;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
