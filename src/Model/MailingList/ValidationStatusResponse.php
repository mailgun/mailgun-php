<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList;

use Mailgun\Model\ApiResponse;

final class ValidationStatusResponse implements ApiResponse
{
    private $createdAt;
    private $downloadUrl;
    private $id;
    private $quantity;
    private $recordsProcessed;
    private $status;
    private $summary;

    public static function create(array $data): self
    {
        $model = new self();
        $model->id = $data['id'] ?? null;
        $model->createdAt = $data['created_at'] ?? null;
        $model->downloadUrl = ValidationStatusDownloadUrl::create($data['download_url']);
        $model->id = $data['id'] ?? null;
        $model->quantity = $data['quantity'] ?? 0;
        $model->recordsProcessed = $data['records_processed'] ?? null;
        $model->status = $data['status'] ?? null;
        $model->summary = ValidationStatusSummary::create($data['summary'] ?? []);

        return $model;
    }

    private function __construct()
    {
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getDownloadUrl(): ValidationStatusDownloadUrl
    {
        return $this->downloadUrl;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getRecordsProcessed(): ?int
    {
        return $this->recordsProcessed;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getSummary(): ValidationStatusSummary
    {
        return $this->summary;
    }
}
