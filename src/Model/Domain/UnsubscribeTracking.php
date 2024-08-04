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
    private string $active;
    private string $htmlFooter;
    private string $textFooter;

    public static function create(array $data): self
    {
        $model = new self();
        $model->active = ($data['active'] ?? null) ? 'yes' : 'no';
        $model->htmlFooter = $data['html_footer'] ?? '';
        $model->textFooter = $data['text_footer'] ?? '';

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return 'yes' === $this->getActive();
    }

    /**
     * @return string
     */
    public function getActive(): string
    {
        return $this->active;
    }

    /**
     * @return string
     */
    public function getHtmlFooter(): string
    {
        return $this->htmlFooter;
    }

    /**
     * @return string
     */
    public function getTextFooter(): string
    {
        return $this->textFooter;
    }
}
