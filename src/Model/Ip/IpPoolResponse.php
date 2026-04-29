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

final class IpPoolResponse implements ApiResponse
{
    private string $poolId;

    private string $name;

    private string $description;

    /** @var string[] */
    private array $ips;

    private bool $isInherited;

    private bool $isLinked;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->poolId = $data['pool_id'] ?? '';
        $model->name = $data['name'] ?? '';
        $model->description = $data['description'] ?? '';
        $model->ips = $data['ips'] ?? [];
        $model->isInherited = (bool) ($data['is_inherited'] ?? false);
        $model->isLinked = (bool) ($data['is_linked'] ?? false);

        return $model;
    }

    public function getPoolId(): string
    {
        return $this->poolId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /** @return string[] */
    public function getIps(): array
    {
        return $this->ips;
    }

    public function isInherited(): bool
    {
        return $this->isInherited;
    }

    public function isLinked(): bool
    {
        return $this->isLinked;
    }
}
