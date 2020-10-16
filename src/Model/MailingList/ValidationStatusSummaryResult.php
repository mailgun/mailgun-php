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

final class ValidationStatusSummaryResult implements ApiResponse
{
    private $deliverable;
    private $doNotSend;
    private $undeliverable;
    private $unknown;

    public static function create(array $data): self
    {
        $model = new self();
        $model->deliverable = $data['deliverable'] ?? null;
        $model->doNotSend = $data['do_not_send'] ?? null;
        $model->undeliverable = $data['undeliverable'] ?? null;
        $model->unknown = $data['unknown'] ?? null;

        return $model;
    }

    private function __construct()
    {
    }

    public function getDeliverable(): ?int
    {
        return $this->deliverable;
    }

    public function getDoNotSend(): ?int
    {
        return $this->doNotSend;
    }

    public function getUndeliverable(): ?int
    {
        return $this->undeliverable;
    }

    public function getUnknown(): ?int
    {
        return $this->unknown;
    }
}
