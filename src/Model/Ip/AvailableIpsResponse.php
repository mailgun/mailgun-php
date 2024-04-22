<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Ip;

use Mailgun\Model\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class AvailableIpsResponse implements ApiResponse
{
    /**
     * @var int
     */
    private $dedicated;

    /**
     * @var int
     */
    private $shared;

    private function __construct()
    {
    }

    /**
     * @param array $data
     * @return AvailableIpsResponse
     */
    public static function create(array $data)
    {
        $model = new self();
        $model->dedicated = $data['allowed']['dedicated'] ?? 0;
        $model->shared = $data['allowed']['shared'] ?? 0;
        return $model;
    }

    /**
     * @return int
     */
    public function getDedicated(): int
    {
        return $this->dedicated;
    }

    /**
     * @return int
     */
    public function getShared(): int
    {
        return $this->shared;
    }
}
