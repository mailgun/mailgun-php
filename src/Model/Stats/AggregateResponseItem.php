<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Stats;

use Mailgun\Model\ApiResponse;

final class AggregateResponseItem implements ApiResponse
{
    private int $accepted;
    private int $clicked;
    private int $complained;
    private int $delivered;
    private int $opened;
    private int $uniqueClicked;
    private int $uniqueOpened;
    private int $unsubscribed;
    private string $domain;
    private string $device;
    private string $country;


    private function __construct()
    {
    }

    /**
     * @param array $data
     * @return self
     */
    public static function create(array $data): self
    {
        $model = new self();
        $model->setClicked($data['clicked'] ?? 0);
        $model->setComplained($data['complained'] ?? 0);
        $model->setDelivered($data['delivered'] ?? 0);
        $model->setOpened($data['opened'] ?? 0);
        $model->setUniqueClicked($data['unique_clicked'] ?? 0);
        $model->setUniqueOpened($data['unique_opened'] ?? 0);
        $model->setUnsubscribed($data['unsubscribed'] ?? 0);
        $model->setAccepted($data['accepted'] ?? 0);
        $model->setDomain($data['domain'] ?? '');
        $model->setDevice($data['device'] ?? '');
        $model->setCountry($data['country'] ?? '');

        return $model;
    }

    /**
     * @return int
     */
    public function getAccepted(): int
    {
        return $this->accepted;
    }

    /**
     * @param int $accepted
     * @return void
     */
    public function setAccepted(int $accepted): void
    {
        $this->accepted = $accepted;
    }

    /**
     * @return int
     */
    public function getClicked(): int
    {
        return $this->clicked;
    }

    /**
     * @param int $clicked
     * @return void
     */
    public function setClicked(int $clicked): void
    {
        $this->clicked = $clicked;
    }

    /**
     * @return int
     */
    public function getComplained(): int
    {
        return $this->complained;
    }

    /**
     * @param int $complained
     * @return void
     */
    public function setComplained(int $complained): void
    {
        $this->complained = $complained;
    }

    /**
     * @return int
     */
    public function getDelivered(): int
    {
        return $this->delivered;
    }

    /**
     * @param int $delivered
     * @return void
     */
    public function setDelivered(int $delivered): void
    {
        $this->delivered = $delivered;
    }

    /**
     * @return int
     */
    public function getOpened(): int
    {
        return $this->opened;
    }

    /**
     * @param int $opened
     * @return void
     */
    public function setOpened(int $opened): void
    {
        $this->opened = $opened;
    }

    /**
     * @return int
     */
    public function getUniqueClicked(): int
    {
        return $this->uniqueClicked;
    }

    /**
     * @param int $uniqueClicked
     * @return void
     */
    public function setUniqueClicked(int $uniqueClicked): void
    {
        $this->uniqueClicked = $uniqueClicked;
    }

    /**
     * @return int
     */
    public function getUniqueOpened(): int
    {
        return $this->uniqueOpened;
    }

    /**
     * @param int $uniqueOpened
     * @return void
     */
    public function setUniqueOpened(int $uniqueOpened): void
    {
        $this->uniqueOpened = $uniqueOpened;
    }

    /**
     * @return int
     */
    public function getUnsubscribed(): int
    {
        return $this->unsubscribed;
    }

    /**
     * @param int $unsubscribed
     * @return void
     */
    public function setUnsubscribed(int $unsubscribed): void
    {
        $this->unsubscribed = $unsubscribed;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return void
     */
    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getDevice(): string
    {
        return $this->device;
    }

    /**
     * @param string $device
     * @return void
     */
    public function setDevice(string $device): void
    {
        $this->device = $device;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return void
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }
}
