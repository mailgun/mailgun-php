<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Whitelist;

use Mailgun\Model\ApiResponse;

/**
 * @author Artem Bondarenko <artem@uartema.com>
 */
final class DeleteResponse implements ApiResponse
{
    private $value;
    private $message;

    final private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->value = $data['value'] ?? '';
        $model->message = $data['message'] ?? '';

        return $model;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
