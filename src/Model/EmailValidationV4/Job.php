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

class Job implements ApiResponse
{
    /**
     * @var DateTimeImmutable|null
     */
    private $createdAt;

    /**
     * @var JobDownloadUrl
     */
    private $downloadUrl;

    /**
     * @var string|null
     */
    private $id;

    /**
     * @var int
     */
    private $quantity = 0;

    /**
     * @var int
     */
    private $recordsProcessed = 0;

    /**
     * @var string|null
     */
    private $status;

    /**
     * @var Summary
     */
    private $summary;

    final private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new static();

        $model->createdAt = isset($data['created_at']) ? new DateTimeImmutable($data['created_at']) : null;
        $model->downloadUrl = JobDownloadUrl::create($data['download_url']);
        $model->id = $data['id'] ?? null;
        $model->quantity = $data['quantity'] ?? null;
        $model->recordsProcessed = $data['records_processed'] ?? null;
        $model->status = $data['status'] ?? null;
        $model->summary = Summary::create($data['summary']);

        return $model;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getDownloadUrl(): JobDownloadUrl
    {
        return $this->downloadUrl;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getRecordsProcessed(): int
    {
        return $this->recordsProcessed;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getSummary(): Summary
    {
        return $this->summary;
    }
}
