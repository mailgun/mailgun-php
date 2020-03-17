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
final class ClickTrackingItem
{
    private $active;
    private $htmlOnly;

    public static function create(array $data): self
    {
        $model = new self();
        if (isset($data['active'])) {
            $model->active = ($data['active'] ? true : false);
            $model->htmlOnly = ('htmlonly' === $data['active']);
        } else {
            $model->active = null;
            $model->htmlOnly = null;
        }

        return $model;
    }

    private function __construct()
    {
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function isHtmlOnly(): ?bool
    {
        return $this->htmlOnly;
    }
}
