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
    private ?string $active;

    public static function create(array $data): self
    {
        $active = $data['active'] ?? null;
        $model = new self();
        $model->active = 'htmlonly' === $active ? $active : ($active ? 'yes' : 'no');

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * @return string|null
     */
    public function getActive(): ?string
    {
        return $this->active;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return 'yes' === $this->getActive();
    }

    /**
     * @return bool
     */
    public function isHtmlOnly(): bool
    {
        return 'htmlonly' === $this->getActive();
    }
}
