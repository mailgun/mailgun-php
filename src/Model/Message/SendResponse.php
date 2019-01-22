<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Message;

use Mailgun\Model\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class SendResponse implements ApiResponse
{
    private $id;
    private $message;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->id = $data['id'] ?? '';
        $model->message = $data['message'] ?? '';

        return $model;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
