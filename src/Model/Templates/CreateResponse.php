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

final class CreateResponse implements ApiResponse
{
    private $message;
    private $template;

    /**
     * @param array $data
     * @return static
     */
    public static function create(array $data): self
    {
        $model = new self();
        $model->message = $data['message'] ?? null;
        $model->template = $data['template'] ?? null;

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return array|null
     */
    public function getTemplate(): ?array
    {
        return $this->template;
    }
}
