<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Domain\Tracking;

/**
 * @author Blake Hancock <blake@osim.digital>
 */
final class OpenTrackingItem
{
    private $active;

    public static function create(array $data): self
    {
        $model = new self();
        $model->active = $data['active'] ?? null;

        return $model;
    }

    private function __construct()
    {
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }
}