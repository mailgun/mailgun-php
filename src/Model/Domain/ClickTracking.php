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
 * Represents a single Click Tracking setting for a domain tracking.
 *
 * @author Artem Bondarenko <artem@uartema.com>
 */
final class ClickTracking
{
    private $active;

    public static function create(array $data): self
    {
        $model = new self();
        $model->active = (bool) ($data['active'] ?? null);

        return $model;
    }

    private function __construct()
    {
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
