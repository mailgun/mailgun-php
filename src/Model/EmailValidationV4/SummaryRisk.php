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

final class SummaryRisk implements ApiResponse
{
    /**
     * @var int
     */
    private $high = 0;

    /**
     * @var int
     */
    private $low = 0;

    /**
     * @var int
     */
    private $medium = 0;

    /**
     * @var int
     */
    private $unknown = 0;

    public static function create(array $data): self
    {
        $model = new self();

        $model->high = $data['high'] ?? 0;
        $model->low = $data['low'] ?? 0;
        $model->medium = $data['medium'] ?? 0;
        $model->unknown = $data['unknown'] ?? 0;

        return $model;
    }

    private function __construct()
    {
    }

    public function getHigh(): int
    {
        return $this->high;
    }

    public function getLow(): int
    {
        return $this->low;
    }

    public function getMedium(): int
    {
        return $this->medium;
    }

    public function getUnknown(): int
    {
        return $this->unknown;
    }
}
