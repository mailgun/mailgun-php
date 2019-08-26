<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Webhook;

use Mailgun\Model\ApiResponse;

/**
 * This is only mean to be the base response for Webhook API.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class BaseResponse implements ApiResponse
{
    private $webhook = [];
    private $message;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new static();
        $model->webhook = $data['webhook'] ?? [];
        $model->message = $data['message'] ?? '';

        return $model;
    }

    public function getWebhookUrl(): ?string
    {
        return $this->webhook['url'] ?? null;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
