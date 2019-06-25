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
    private $legacyBounce = null;
    private $legacyDeliver = null;
    private $legacyDrop = null;
    private $legacySpam = null;
    private $legacyUnsubscribe = null;
    private $legacyClick = null;
    private $legacyOpen = null;
    private $clicked = [];
    private $complained = [];
    private $delivered = [];
    private $opened = [];
    private $permanentFail = [];
    private $temporaryFail = [];
    private $unsubscribed = [];

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();

        $data = $data['webhooks'] ?? $data;

        $model->legacyBounce = $data['bounce']['url'] ?? null;
        $model->legacyDeliver = $data['deliver']['url'] ?? null;
        $model->legacyDrop = $data['drop']['url'] ?? null;
        $model->legacySpam = $data['spam']['url'] ?? null;
        $model->legacyUnsubscribe = $data['unsubscribe']['url'] ?? null;
        $model->legacyClick = $data['click']['url'] ?? null;
        $model->legacyOpen = $data['open']['url'] ?? null;

        $model->clicked = $data['clicked']['urls'] ?? [];
        $model->complained = $data['complained']['urls'] ?? [];
        $model->delivered = $data['delivered']['urls'] ?? [];
        $model->opened = $data['opened']['urls'] ?? [];
        $model->permanentFail = $data['permanent_fail']['urls'] ?? [];
        $model->temporaryFail = $data['temporary_fail']['urls'] ?? [];
        $model->unsubscribed = $data['unsubscribed']['urls'] ?? [];

        return $model;
    }

    public function getBounceUrl(): ?string
    {
        return $this->legacyBounce;
    }

    public function getDeliverUrl(): ?string
    {
        return $this->legacyDeliver;
    }

    public function getDropUrl(): ?string
    {
        return $this->legacyDrop;
    }

    public function getSpamUrl(): ?string
    {
        return $this->legacySpam;
    }

    public function getUnsubscribeUrl(): ?string
    {
        return $this->legacyUnsubscribe;
    }

    public function getClickUrl(): ?string
    {
        return $this->legacyClick;
    }

    public function getOpenUrl(): ?string
    {
        return $this->legacyOpen;
    }

    public function getClickedUrls(): ?array
    {
        return $this->clicked;
    }

    public function getComplainedUrls(): ?array
    {
        return $this->complained;
    }

    public function getDeliveredUrls(): ?array
    {
        return $this->delivered;
    }

    public function getOpenedUrls(): ?array
    {
        return $this->opened;
    }

    public function getPermanentFailUrls(): ?array
    {
        return $this->permanentFail;
    }

    public function getTemporaryFailUrls(): ?array
    {
        return $this->temporaryFail;
    }

    public function getUnsubscribeUrls(): ?array
    {
        return $this->unsubscribed;
    }
}
