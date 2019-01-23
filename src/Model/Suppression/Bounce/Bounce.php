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
        $model = new self();
        $model->address = $data['address'] ?? null;
        $model->code = $data['code'] ?? null;
        $model->error = $data['error'] ?? null;
        $model->createdAt = isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null;

        return $model;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
}
