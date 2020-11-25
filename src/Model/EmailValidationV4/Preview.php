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
     * @var Summary
     */
    private $summary;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new static();

        $model->id = $data['id'] ?? null;
        $model->valid = $data['valid'] ?? null;
        $model->status = $data['status'] ?? null;
        $model->quantity = $data['quantity'] ?? null;
        $model->createdAt = isset($data['created_at']) ? DateTimeImmutable::createFromFormat('U', (string)($data['created_at'])) : null;
        $model->summary = Summary::create($data['summary']);

        return $model;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return bool|null
     */
    public function isValid(): ?bool
    {
        return $this->valid;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getSummary(): Summary
    {
        return $this->summary;
    }
}
