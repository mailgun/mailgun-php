<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\EmailValidationV4;

use DateTimeImmutable;
use Mailgun\Model\ApiResponse;

class Preview implements ApiResponse
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var bool
     */
    private $valid;

    /**
     * @var string|null
     */
    private $status;

    /**
     * @var int
     */
    private $quantity = 0;

    /**
     * @var DateTimeImmutable|null
     */
    private $createdAt;

    /**
     * @var Summary|null
     */
    private $summary;

    final private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new static();

        $model->id = $data['id'] ?? null;
        $model->valid = $data['valid'] ?? null;
        $model->status = $data['status'] ?? null;
        $model->quantity = $data['quantity'] ?? null;
        $model->createdAt = isset($data['created_at']) ? (DateTimeImmutable::createFromFormat('U', (string) ($data['created_at'])) ?: null) : null;
        $model->summary = $data['summary'] ? Summary::create($data['summary']) : null;

        return $model;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getSummary(): ?Summary
    {
        return $this->summary;
    }
}
