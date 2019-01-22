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
final class CountryResponse implements ApiResponse
{
    /**
     * @var array [locale => data[]]
     */
    private $countries;
    private $tag;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->tag = $data['tag'] ?? '';
        $model->countries = $data['countries'] ?? [];

        return $model;
    }

    public function getCountries(): array
    {
        return $this->countries;
    }

    public function getTag(): string
    {
        return $this->tag;
    }
}
