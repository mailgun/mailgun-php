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

final class SandboxAuthRecipientsResponse implements ApiResponse
{
    private array $recipients;

    /**
     * @param array $data
     * @return self
     */
    public static function create(array $data): self
    {
        $model = new self();
        $model->recipients = $data['recipients'] ?? $data['recipient'] ?? [];

        return $model;
    }

    /**
     * @return array
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    private function __construct()
    {
    }
}
