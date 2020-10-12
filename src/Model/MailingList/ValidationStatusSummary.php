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

final class ValidationStatusSummary implements ApiResponse
{
    private $result;
    private $risk;

    public static function create(array $data): self
    {
        $model = new self();
        $model->result = ValidationStatusSummaryResult::create($data['result'] ?? []);
        $model->risk = ValidationStatusSummaryRisk::create($data['risk'] ?? []);

        return $model;
    }

    private function __construct()
    {
    }

    public function getResult(): ValidationStatusSummaryResult
    {
        return $this->result;
    }

    public function getRisk(): ValidationStatusSummaryRisk
    {
        return $this->risk;
    }
}
