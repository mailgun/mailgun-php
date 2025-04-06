<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\AccountManagement;

use Mailgun\Model\ApiResponse;

final class AccountResponse implements ApiResponse
{
    private string $message;

    public static function create(array $data): self
    {
        $model = new self();
        $model->message = $data['message'] ?? '';

        return $model;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    private function __construct()
    {
    }
}
