<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Stats;

use Mailgun\Model\ApiResponse;

final class AggregateDevicesResponse implements ApiResponse
{
    private array $devices = [];

    private function __construct()
    {
    }

    /**
     * @param array $data
     * @return self
     * @throws \Exception
     */
    public static function create(array $data): self
    {
        $devices = [];
        foreach ($data['devices'] as $domain => $provider) {
            $devices[] = AggregateResponseItem::create($provider + ['device' => $domain]);
        }
        $model = new self();
        $model->setDevices($devices);
        return $model;
    }

    /**
     * @return array|AggregateResponseItem[]
     */
    public function getDevices(): array
    {
        return $this->devices;
    }

    /**
     * @param array $devices
     * @return void
     */
    public function setDevices(array $devices): void
    {
        $this->devices = $devices;
    }

}
