<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Stats;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class TotalResponseItem
{
    private $time;
    private $accepted;
    private $delivered;
    private $failed;
    private $complained;
    private $opened;
    private $clicked;
    private $unsubscribed;
    private $stored;

    public static function create(array $data): self
    {
        $model = new self();
        $model->time = isset($data['time']) ? new \DateTimeImmutable($data['time']) : null;
        $model->accepted = $data['accepted'] ?? [];
        $model->delivered = $data['delivered'] ?? [];
        $model->failed = $data['failed'] ?? [];
        $model->complained = $data['complained'] ?? [];
        $model->opened = $data['opened'] ?? [];
        $model->clicked = $data['clicked'] ?? [];
        $model->unsubscribed = $data['unsubscribed'] ?? [];
        $model->stored = $data['stored'] ?? [];

        return $model;
    }

    private function __construct()
    {
    }

    public function getTime(): ?\DateTimeImmutable
    {
        return $this->time;
    }

    public function getAccepted(): array
    {
        return $this->accepted;
    }

    public function getDelivered(): array
    {
        return $this->delivered;
    }

    public function getFailed(): array
    {
        return $this->failed;
    }

    public function getComplained(): array
    {
        return $this->complained;
    }

    public function getOpened(): array
    {
        return $this->opened;
    }

    public function getClicked(): array
    {
        return $this->clicked;
    }

    public function getUnsubscribed(): array
    {
        return $this->unsubscribed;
    }

    public function getStored(): array
    {
        return $this->stored;
    }
}
