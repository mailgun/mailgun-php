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

final class ValidationStatusSummaryRisk implements ApiResponse
{
    private $high;
    private $low;
    private $medium;
    private $unknown;

    public static function create(array $data): self
    {
        $model = new self();
        $model->high = $data['high'] ?? null;
        $model->low = $data['low'] ?? null;
        $model->medium = $data['medium'] ?? null;
        $model->unknown = $data['unknown'] ?? null;

        return $model;
    }

    private function __construct()
    {
    }

    public function getHigh(): ?int
    {
        return $this->high;
    }

    public function getLow(): ?int
    {
        return $this->low;
    }

    public function getMedium(): ?int
    {
        return $this->medium;
    }

    public function getUnknown(): ?int
    {
        return $this->unknown;
    }
}
