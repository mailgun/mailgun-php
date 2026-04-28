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
 * Represents a single event in a Dynamic IP Pools history response.
 */
final class HistoryItem
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $owningAccountId;

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
    private $domainId;

    /**
     * @var string
     */
    private $domainName;

    /**
     * @var string
     */
    private $newBand;

    /**
     * @var string
     */
    private $prevBand;

    /**
     * @var string
     */
    private $reason;

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

    /**
     * @var string
     */
    private $initiatedBy;

    /**
     * @var string
     */
    private $timestamp;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->id = $data['id'] ?? '';
        $model->owningAccountId = $data['owning_account_id'] ?? '';
        $model->accountId = $data['account_id'] ?? '';
        $model->accountName = $data['account_name'] ?? '';
        $model->domainId = $data['domain_id'] ?? '';
        $model->domainName = $data['domain_name'] ?? '';
        $model->newBand = $data['new_band'] ?? '';
        $model->prevBand = $data['prev_band'] ?? '';
        $model->reason = $data['reason'] ?? '';
        $model->bounceRate = isset($data['bounce_rate']) ? (float) $data['bounce_rate'] : null;
        $model->complaintRate = isset($data['complaint_rate']) ? (float) $data['complaint_rate'] : null;
        $model->processedCount = isset($data['processed_count']) ? (int) $data['processed_count'] : null;
        $model->initiatedBy = $data['initiated_by'] ?? '';
        $model->timestamp = $data['timestamp'] ?? '';

        return $model;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOwningAccountId(): string
    {
        return $this->owningAccountId;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getAccountName(): string
    {
        return $this->accountName;
    }

    public function getDomainId(): string
    {
        return $this->domainId;
    }

    public function getDomainName(): string
    {
        return $this->domainName;
    }

    public function getNewBand(): string
    {
        return $this->newBand;
    }

    public function getPrevBand(): string
    {
        return $this->prevBand;
    }

    public function getReason(): string
    {
        return $this->reason;
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

    public function getInitiatedBy(): string
    {
        return $this->initiatedBy;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }
}
