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

final class AggregateCountriesResponse implements ApiResponse
{
    private array $countries = [];

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
        $countries = [];
        foreach ($data['countries'] as $country => $provider) {
            $countries[] = AggregateResponseItem::create($provider + ['country' => $country]);
        }
        $model = new self();
        $model->setCountries($countries);
        return $model;
    }

    /**
     * @return array|AggregateResponseItem[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @param array $countries
     * @return void
     */
    public function setCountries(array $countries): void
    {
        $this->countries = $countries;
    }


}
