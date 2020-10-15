<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Whitelist;

use DateTimeImmutable;

/**
 * @author Artem Bondarenko <artem@uartema.com>
 */
class Whitelist
{
    private $value;
    private $reason;
    private $type;
    private $createdAt;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->value = $data['value'] ?? null;
        $model->reason = $data['reason'] ?? null;
        $model->type = $data['type'] ?? null;
        $model->createdAt = isset($data['createdAt']) ? new DateTimeImmutable($data['createdAt']) : null;

        return $model;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }
}
