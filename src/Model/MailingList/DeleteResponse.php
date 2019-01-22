<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList;

use Mailgun\Model\ApiResponse;

final class DeleteResponse implements ApiResponse
{
    private $message;
    private $address;

    public static function create(array $data): self
    {
        $model = new self();
        $model->address = $data['address'] ?? '';
        $model->message = $data['message'] ?? '';

        return $model;
    }

    private function __construct()
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
