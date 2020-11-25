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

final class Summary implements ApiResponse
{
    /**
     * @var SummaryResult
     */
    private $result;

    /**
     * @var SummaryRisk
     */
    private $risk;

    public static function create(array $data): self
    {
        $model = new self();

        $model->result = SummaryResult::create($data['result']);
        $model->risk = SummaryRisk::create($data['risk']);

        return $model;
    }

    private function __construct()
    {
    }

    public function getResult(): SummaryResult
    {
        return $this->result;
    }

    public function getRisk(): SummaryRisk
    {
        return $this->risk;
    }
}
