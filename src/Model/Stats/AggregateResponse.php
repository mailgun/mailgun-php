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

final class AggregateResponse implements ApiResponse
{
    private array $providers = [];

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
        $providers = [];
        foreach ($data['providers'] as $domain => $provider) {
            $providers[] = AggregateResponseItem::create($provider + ['domain' => $domain]);
        }
        $model = new self();
        $model->setProviders($providers);

        return $model;
    }

    /**
     * @return array|AggregateResponseItem[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    /**
     * @param array $providers
     * @return void
     */
    public function setProviders(array $providers): void
    {
        $this->providers = $providers;
    }

}
