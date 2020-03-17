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
final class UnsubscribeTrackingItem
{
    private $active;
    private $htmlFooter;
    private $textFooter;

    public static function create(array $data): self
    {
        $model = new self();
        $model->active = $data['active'] ?? null;
        $model->htmlFooter = $data['html_footer'] ?? null;
        $model->textFooter = $data['text_footer'] ?? null;

        return $model;
    }

    private function __construct()
    {
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function getHtmlFooter(): ?string
    {
        return $this->htmlFooter;
    }

    public function getTextFooter(): ?string
    {
        return $this->textFooter;
    }
}
