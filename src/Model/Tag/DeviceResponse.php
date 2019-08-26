<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Tag;

use Mailgun\Model\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class DeviceResponse implements ApiResponse
{
    /**
     * @var array [name => data[]]
     */
    private $devices;
    private $tag;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->tag = $data['tag'] ?? '';
        $model->devices = $data['devices'] ?? [];

        return $model;
    }

    public function getDevices(): array
    {
        return $this->devices;
    }

    public function getTag(): string
    {
        return $this->tag;
    }
}
