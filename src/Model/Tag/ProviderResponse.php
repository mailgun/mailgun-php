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
final class ProviderResponse implements ApiResponse
{
    /**
     * @var array [name => data[]]
     */
    private $providers;
    private $tag;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->tag = $data['tag'] ?? '';
        $model->providers = $data['providers'] ?? [];

        return $model;
    }

    public function getProviders(): array
    {
        return $this->providers;
    }

    public function getTag(): string
    {
        return $this->tag;
    }
}
