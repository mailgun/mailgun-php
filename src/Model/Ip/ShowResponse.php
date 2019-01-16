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
final class ShowResponse implements ApiResponse
{
    private $ip;
    private $dedicated;
    private $rdns;

    private function __construct()
    {
    }

    public static function create(array $data)
    {
        $model = new self();
        $model->ip = $data['ip'];
        $model->dedicated = (bool) $data['dedicated'];
        $model->rdns = $data['rdns'];

        return $model;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getDedicated(): bool
    {
        return $this->dedicated;
    }

    public function getRdns(): string
    {
        return $this->rdns;
    }
}
