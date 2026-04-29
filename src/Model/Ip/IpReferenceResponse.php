<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Ip;

use Mailgun\Model\ApiResponse;

final class IpReferenceResponse implements ApiResponse
{
    private string $message;

    private string $referenceId;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->message = $data['message'] ?? '';
        $model->referenceId = $data['reference_id'] ?? '';

        return $model;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }
}
