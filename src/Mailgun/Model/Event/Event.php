<?php

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
class Event
{
    /**
     * @var string status
     */
    private $event;

    /**
     * @var string
     */
    private $id;

    /**
     * @var float
     */
    private $timestamp;

    /**
     * A \DateTime representation of $timestamp.
     *
     * @var \DateTime
     */
    private $eventDate;

    /**
     * @var array|string[]
     */
    private $tags = [];

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $severity;

    /**
     * @var array
     */
    private $envelope = [];

    /**
     * @var array
     */
    private $deliveryStatus;

    /**
     * @var array|string[]
     */
    private $campaigns = [];

    /**
     * @var string
     */
    private $ip;

    /**
     * @var array
     */
    private $clientInfo = [];

    /**
     * @var string
     */
    private $reason;

    /**
     * @var array
     */
    private $userVariables = [];

    /**
     * @var array key=>bool
     */
    private $flags = [];

    /**
     * @var array multi dimensions
     */
    private $routes = [];

    /**
     * @var array multi dimensions
     */
    private $message = [];

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var array
     */
    private $geolocation = [];

    /**
     * @var array
     */
    private $storage = [];

    /**
     * @var string
     */
    private $method;

    /**
     * @param string $event
     * @param string $id
     * @param float  $timestamp
     */
    public function __construct($event, $id, $timestamp)
    {
        $this->event = $event;
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->eventDate = new \DateTime();
        $this->eventDate->setTimestamp((int) $timestamp);
    }

    /**
     * @param array $data
     *
     * @return Event
     */
    public static function create(array $data)
    {
        $event = new self($data['event'], $data['id'], $data['timestamp']);

        if (isset($data['tags'])) {
            $event->setTags($data['tags']);
        }
        if (isset($data['envelope'])) {
            $event->setEnvelope($data['envelope']);
        }
        if (isset($data['campaigns'])) {
            $event->setCampaigns($data['campaigns']);
        }
        if (isset($data['user-variables'])) {
            $event->setUserVariables($data['user-variables']);
        }
        if (isset($data['flags'])) {
            $event->setFlags($data['flags']);
        }
        if (isset($data['routes'])) {
            $event->setRoutes($data['routes']);
        }
        if (isset($data['message'])) {
            $event->setMessage($data['message']);
        }
        if (isset($data['recipient'])) {
            $event->setRecipient($data['recipient']);
        }
        if (isset($data['method'])) {
            $event->setMethod($data['method']);
        }
        if (isset($data['delivery-status'])) {
            $event->setDeliveryStatus($data['delivery-status']);
        }
        if (isset($data['severity'])) {
            $event->setSeverity($data['severity']);
        }
        if (isset($data['reason'])) {
            $event->setReason($data['reason']);
        }
        if (isset($data['geolocation'])) {
            $event->setGeolocation($data['geolocation']);
        }
        if (isset($data['ip'])) {
            $event->setIp($data['ip']);
        }
        if (isset($data['client-info'])) {
            $event->setClientInfo($data['client-info']);
        }
        if (isset($data['url'])) {
            $event->setUrl($data['url']);
        }
        if (isset($data['storage'])) {
            $event->setStorage($data['storage']);
        }

        return $event;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return \DateTime
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * @return array|\string[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array|\string[] $tags
     */
    private function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    private function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getEnvelope()
    {
        return $this->envelope;
    }

    /**
     * @param array $envelope
     */
    private function setEnvelope($envelope)
    {
        $this->envelope = $envelope;
    }

    /**
     * @return array
     */
    public function getDeliveryStatus()
    {
        return $this->deliveryStatus;
    }

    /**
     * @param array $deliveryStatus
     */
    private function setDeliveryStatus($deliveryStatus)
    {
        $this->deliveryStatus = $deliveryStatus;
    }

    /**
     * @return array|\string[]
     */
    public function getCampaigns()
    {
        return $this->campaigns;
    }

    /**
     * @param array|\string[] $campaigns
     */
    private function setCampaigns($campaigns)
    {
        $this->campaigns = $campaigns;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    private function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return array
     */
    public function getClientInfo()
    {
        return $this->clientInfo;
    }

    /**
     * @param array $clientInfo
     */
    private function setClientInfo($clientInfo)
    {
        $this->clientInfo = $clientInfo;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    private function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * @return array
     */
    public function getUserVariables()
    {
        return $this->userVariables;
    }

    /**
     * @param array $userVariables
     */
    private function setUserVariables($userVariables)
    {
        $this->userVariables = $userVariables;
    }

    /**
     * @return array
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @param array $flags
     */
    private function setFlags($flags)
    {
        $this->flags = $flags;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param array $routes
     */
    private function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    /**
     * @return array
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param array $message
     */
    private function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     */
    private function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return array
     */
    public function getGeolocation()
    {
        return $this->geolocation;
    }

    /**
     * @param array $geolocation
     */
    private function setGeolocation($geolocation)
    {
        $this->geolocation = $geolocation;
    }

    /**
     * @return array
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param array $storage
     */
    private function setStorage($storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    private function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * @param string $severity
     */
    private function setSeverity($severity)
    {
        $this->severity = $severity;
    }
}
