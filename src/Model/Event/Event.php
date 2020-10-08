<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Event;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Event
{
    private $event;
    private $id;
    private $timestamp;
    private $eventDate;
    private $tags;
    private $url;
    private $severity;
    private $envelope;
    private $deliveryStatus;
    private $campaigns;
    private $ip;
    private $clientInfo;
    private $reason;
    private $userVariables;
    private $flags;
    private $routes;
    private $message;
    private $recipient;
    private $geolocation;
    private $storage;
    private $method;
    private $logLevel;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->event = $data['event'];
        $model->id = $data['id'];
        $model->timestamp = (int) $data['timestamp'];
        $model->eventDate = (new \DateTimeImmutable())->setTimestamp((int) $data['timestamp']);
        $model->tags = $data['tags'] ?? [];
        $model->envelope = $data['envelope'] ?? [];
        $model->campaigns = $data['campaigns'] ?? [];
        $model->userVariables = $data['user-variables'] ?? [];
        $model->flags = $data['flags'] ?? [];
        $model->routes = $data['routes'] ?? [];
        $model->message = $data['message'] ?? [];
        $model->recipient = $data['recipient'] ?? '';
        $model->method = $data['method'] ?? '';
        $model->deliveryStatus = $data['delivery-status'] ?? [];
        $model->severity = $data['severity'] ?? '';
        $model->reason = $data['reason'] ?? '';
        $model->geolocation = $data['geolocation'] ?? [];
        $model->ip = $data['ip'] ?? '';
        $model->clientInfo = $data['client-info'] ?? [];
        $model->url = $data['url'] ?? '';
        $model->storage = $data['storage'] ?? [];
        $model->logLevel = $data['log-level'] ?? '';

        return $model;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * A \DateTimeImmutable representation of $timestamp.
     */
    public function getEventDate(): \DateTimeImmutable
    {
        return $this->eventDate;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getSeverity(): string
    {
        return $this->severity;
    }

    public function getEnvelope(): array
    {
        return $this->envelope;
    }

    public function getDeliveryStatus(): array
    {
        return $this->deliveryStatus;
    }

    /**
     * @return string[]
     */
    public function getCampaigns(): array
    {
        return $this->campaigns;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getClientInfo(): array
    {
        return $this->clientInfo;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getUserVariables(): array
    {
        return $this->userVariables;
    }

    /**
     * key=>bool.
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /**
     * multi dimensions.
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * multi dimensions.
     */
    public function getMessage(): array
    {
        return $this->message;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getGeolocation(): array
    {
        return $this->geolocation;
    }

    public function getStorage(): array
    {
        return $this->storage;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getLogLevel(): string
    {
        return $this->logLevel;
    }
}
