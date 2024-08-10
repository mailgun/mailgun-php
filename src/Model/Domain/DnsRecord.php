<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Domain;

/**
 * Represents a single DNS record for a domain.
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
final class DnsRecord
{
    private ?string $name;
    private ?string $type;
    private ?string $value;
    private ?string $priority;
    private ?string $valid;
    private array $cached;

    public static function create(array $data): self
    {
        $model = new self();
        $model->name = $data['name'] ?? null;
        $model->type = $data['record_type'] ?? null;
        $model->value = $data['value'] ?? null;
        $model->priority = $data['priority'] ?? null;
        $model->valid = $data['valid'] ?? null;
        $model->cached = $data['cached'] ?? [];

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * name of the record, as used in CNAME, etc.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * DNS record type.
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * DNS record value.
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Record priority, used for MX.
     */
    public function getPriority(): ?string
    {
        return $this->priority;
    }

    /**
     * DNS record has been added to domain DNS?
     */
    public function isValid(): bool
    {
        return 'valid' === $this->valid;
    }

    public function getValidity(): ?string
    {
        return $this->valid;
    }

    /**
     * DNS record current value.
     */
    public function getCached(): array
    {
        return $this->cached;
    }
}
