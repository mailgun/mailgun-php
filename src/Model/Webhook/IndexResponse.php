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
    private $legacy_bounce = [];
    private $legacy_deliver = [];
    private $legacy_drop = [];
    private $legacy_spam = [];
    private $legacy_unsubscribe = [];
    private $legacy_click = [];
    private $legacy_open = [];

    private $clicked = [];
    private $complained = [];
    private $delivered = [];
    private $opened = [];
    private $permanent_fail = [];
    private $temporary_fail = [];
    private $unsubscribed = [];

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();

        $data = $data['webhooks'] ?? $data;

        $model->legacy_bounce = $data['bounce'] ?? [];
        $model->legacy_deliver = $data['deliver'] ?? [];
        $model->legacy_drop = $data['drop'] ?? [];
        $model->legacy_spam = $data['spam'] ?? [];
        $model->legacy_unsubscribe = $data['unsubscribe'] ?? [];
        $model->legacy_click = $data['click'] ?? [];
        $model->legacy_open = $data['open'] ?? [];

        $model->clicked = $data['clicked'] ?? [];
        $model->complained = $data['complained'] ?? [];
        $model->delivered = $data['delivered'] ?? [];
        $model->opened = $data['opened'] ?? [];
        $model->permanent_fail = $data['permanent_fail'] ?? [];
        $model->temporary_fail = $data['temporary_fail'] ?? [];
        $model->unsubscribed = $data['unsubscribed'] ?? [];

        return $model;
    }

    public function getBounceUrl(): ?string
    {
        return $this->legacy_bounce['url'] ?? null;
    }

    public function getDeliverUrl(): ?string
    {
        return $this->legacy_deliver['url'] ?? null;
    }

    public function getDropUrl(): ?string
    {
        return $this->legacy_drop['url'] ?? null;
    }

    public function getSpamUrl(): ?string
    {
        return $this->legacy_spam['url'] ?? null;
    }

    public function getUnsubscribeUrl()
    {
        return $this->legacy_unsubscribe['url'] ?? null;
    }

    public function getClickUrl(): ?string
    {
        return $this->legacy_click['url'] ?? null;
    }

    public function getOpenUrl(): ?string
    {
        return $this->legacy_open['url'] ?? null;
    }


    public function getClickedUrls(): ?array
    {
        return $this->clicked['urls'] ?? null;
    }

    public function getComplainedUrls(): ?array
    {
        return $this->complained['urls'] ?? null;
    }

    public function getDeliveredUrls(): ?array
    {
        return $this->delivered['urls'] ?? null;
    }

    public function getOpenedUrls(): ?array
    {
        return $this->opened['urls'] ?? null;
    }

    public function getPermanentFailUrls(): ?array
    {
        return $this->permanent_fail['urls'] ?? null;
    }

    public function getTemporaryFailUrls(): ?array
    {
        return $this->temporary_fail['urls'] ?? null;
    }

    public function getUnsubscribeUrls(): ?array
    {
        return $this->unsubscribed['urls'] ?? null;
    }
}
