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
 * Represents a single Unsubscribe Tracking setting for a domain tracking.
 *
 * @author Artem Bondarenko <artem@uartema.com>
 */
final class UnsubscribeTracking
{
    private $active;
    private $htmlFooter;
    private $textFooter;

    public static function create(array $data): self
    {
        $model = new self();
        $model->active = (bool) ($data['active'] ?? null);
        $model->htmlFooter = $data['html_footer'] ?? '';
        $model->textFooter = $data['text_footer'] ?? '';

        return $model;
    }

    private function __construct()
    {
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getHtmlFooter(): string
    {
        return $this->htmlFooter;
    }

    public function getTextFooter(): string
    {
        return $this->textFooter;
    }
}
