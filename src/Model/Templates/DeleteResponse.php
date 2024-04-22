<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Templates;

use Mailgun\Model\ApiResponse;

final class DeleteResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var Template
     */
    private $template;

    /**
     * @param  array  $data
     * @return static
     */
    public static function create(array $data): self
    {
        $template = $data['template'] ?? $data;
        $model = new self();
        $model->setMessage($data['message']);
        $model->setTemplate(Template::create($template));

        return $model;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return Template
     */
    public function getTemplate(): Template
    {
        return $this->template;
    }

    /**
     * @param Template $template
     */
    public function setTemplate(Template $template): void
    {
        $this->template = $template;
    }
}
