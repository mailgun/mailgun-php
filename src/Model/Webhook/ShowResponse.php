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
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ShowResponse implements ApiResponse
{
    private $webhook = [];

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();

        $model->webhook = $data['webhook'] ?? [];

        return $model;
    }

    public function getWebhookUrl(): ?string
    {
        return $this->webhook['url'] ?? null;
    }
}
