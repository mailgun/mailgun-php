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
final class IndexResponse implements ApiResponse
{
    private $bounce = [];
    private $deliver = [];
    private $drop = [];
    private $spam = [];
    private $unsubscribe = [];
    private $click = [];
    private $open = [];

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();

        $data = $data['webhooks'] ?? $data;

        $model->bounce = $data['bounce'] ?? [];
        $model->deliver = $data['deliver'] ?? [];
        $model->drop = $data['drop'] ?? [];
        $model->spam = $data['spam'] ?? [];
        $model->unsubscribe = $data['unsubscribe'] ?? [];
        $model->click = $data['click'] ?? [];
        $model->open = $data['open'] ?? [];

        return $model;
    }

    public function getBounceUrl(): ?string
    {
        return $this->bounce['url'] ?? null;
    }

    public function getDeliverUrl(): ?string
    {
        return $this->deliver['url'] ?? null;
    }

    public function getDropUrl(): ?string
    {
        return $this->drop['url'] ?? null;
    }

    public function getSpamUrl(): ?string
    {
        return $this->spam['url'] ?? null;
    }

    public function getUnsubscribeUrl()
    {
        return $this->unsubscribe['url'] ?? null;
    }

    public function getClickUrl(): ?string
    {
        return $this->click['url'] ?? null;
    }

    public function getOpenUrl(): ?string
    {
        return $this->open['url'] ?? null;
    }
}
