<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Domain;

/**
 * Represents a single Open Tracking setting for a domain tracking.
 *
 * @author Artem Bondarenko <artem@uartema.com>
 */
final class OpenTracking
{
    private $active;

    public static function create(array $data): self
    {
        $model = new self();
        $active = $data['active'] ?? null;

        if (true === $active) {
            $model->active = 'yes';
        } elseif (false === $active) {
            $model->active = 'no';
        } else {
            $model->active = $active;
        }

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * @return string
     */
    public function getActive(): ?string
    {
        return $this->active;
    }

    public function isActive(): bool
    {
        return $this->getActive() === 'yes';
    }
}
