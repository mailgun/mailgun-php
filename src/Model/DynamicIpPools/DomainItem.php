<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\DynamicIpPools;

/**
 * Represents a single domain entry in a Dynamic IP Pools domains response.
 */
final class DomainItem
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $accountId;

    /**
     * @var string
     */
    private $accountName;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $registeredAt;

    /**
     * @var string
     */
    private $pool;

    /**
     * @var bool
     */
    private $override;

    /**
     * @var float|null
     */
    private $bounceRate;

    /**
     * @var float|null
     */
    private $complaintRate;

    /**
     * @var int|null
     */
    private $processedCount;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->id = $data['id'] ?? '';
        $model->accountId = $data['account_id'] ?? '';
        $model->accountName = $data['account_name'] ?? '';
        $model->name = $data['name'] ?? '';
        $model->registeredAt = $data['registered_at'] ?? '';
        $model->pool = $data['pool'] ?? '';
        $model->override = (bool) ($data['override'] ?? false);
        $model->bounceRate = isset($data['bounce_rate']) ? (float) $data['bounce_rate'] : null;
        $model->complaintRate = isset($data['complaint_rate']) ? (float) $data['complaint_rate'] : null;
        $model->processedCount = isset($data['processed_count']) ? (int) $data['processed_count'] : null;

        return $model;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getAccountName(): string
    {
        return $this->accountName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRegisteredAt(): string
    {
        return $this->registeredAt;
    }

    public function getPool(): string
    {
        return $this->pool;
    }

    public function isOverride(): bool
    {
        return $this->override;
    }

    public function getBounceRate(): ?float
    {
        return $this->bounceRate;
    }

    public function getComplaintRate(): ?float
    {
        return $this->complaintRate;
    }

    public function getProcessedCount(): ?int
    {
        return $this->processedCount;
    }
}
