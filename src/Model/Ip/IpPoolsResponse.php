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

final class IpPoolsResponse implements ApiResponse
{
    /** @var array[] */
    private array $ipPools;

    private string $message;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->ipPools = $data['ip_pools'] ?? [];
        $model->message = $data['message'] ?? '';

        return $model;
    }

    /** @return array[] */
    public function getIpPools(): array
    {
        return $this->ipPools;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
