<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\EmailValidationV4;

use Mailgun\Model\ApiResponse;

final class SummaryResult implements ApiResponse
{
    /**
     * @var int
     */
    private $deliverable = 0;

    /**
     * @var int
     */
    private $doNotSend = 0;

    /**
     * @var int
     */
    private $undeliverable = 0;

    /**
     * @var int
     */
    private $catchAll = 0;

    /**
     * @var int
     */
    private $unknown = 0;

    public static function create(array $data): self
    {
        $model = new self();
        $model->deliverable = $data['deliverable'] ?? 0;
        $model->doNotSend = $data['do_not_send'] ?? 0;
        $model->undeliverable = $data['undeliverable'] ?? 0;
        $model->catchAll = $data['catch_all'] ?? 0;
        $model->unknown = $data['unknown'] ?? 0;

        return $model;
    }

    private function __construct()
    {
    }

    public function getDeliverable(): int
    {
        return $this->deliverable;
    }

    public function getDoNotSend(): int
    {
        return $this->doNotSend;
    }

    public function getUndeliverable(): int
    {
        return $this->undeliverable;
    }

    public function getCatchAll(): int
    {
        return $this->catchAll;
    }

    public function getUnknown(): int
    {
        return $this->unknown;
    }
}
