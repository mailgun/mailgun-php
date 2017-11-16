<?php

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
class IndexResponse implements ApiResponse
{
    /**
     * @var array
     */
    private $bounce = [];

    /**
     * @var array
     */
    private $deliver = [];

    /**
     * @var array
     */
    private $drop = [];

    /**
     * @var array
     */
    private $spam = [];

    /**
     * @var array
     */
    private $unsubscribe = [];

    /**
     * @var array
     */
    private $click = [];

    /**
     * @var array
     */
    private $open = [];

    /**
     * Do not let this object be creted without the ::create.
     */
    private function __construct()
    {
    }

    /**
     * @param array $data
     *
     * @return IndexResponse
     */
    public static function create(array $data)
    {
        $self = new self();
        $data = isset($data['webhooks']) ? $data['webhooks'] : $data;
        if (isset($data['bounce'])) {
            $self->setBounce($data['bounce']);
        }
        if (isset($data['deliver'])) {
            $self->setDeliver($data['deliver']);
        }
        if (isset($data['drop'])) {
            $self->setDrop($data['drop']);
        }
        if (isset($data['spam'])) {
            $self->setSpam($data['spam']);
        }
        if (isset($data['unsubscribe'])) {
            $self->setUnsubscribe($data['unsubscribe']);
        }
        if (isset($data['click'])) {
            $self->setClick($data['click']);
        }
        if (isset($data['open'])) {
            $self->setOpen($data['open']);
        }

        return $self;
    }

    /**
     * @return string|null
     */
    public function getBounceUrl()
    {
        if (isset($this->bounce['url'])) {
            return $this->bounce['url'];
        }
    }

    /**
     * @param array $bounce
     */
    private function setBounce($bounce)
    {
        $this->bounce = $bounce;
    }

    /**
     * @return string|null
     */
    public function getDeliverUrl()
    {
        if (isset($this->deliver['url'])) {
            return $this->deliver['url'];
        }
    }

    /**
     * @param array $deliver
     */
    private function setDeliver($deliver)
    {
        $this->deliver = $deliver;
    }

    /**
     * @return string|null
     */
    public function getDropUrl()
    {
        if (isset($this->drop['url'])) {
            return $this->drop['url'];
        }
    }

    /**
     * @param array $drop
     */
    private function setDrop($drop)
    {
        $this->drop = $drop;
    }

    /**
     * @return string|null
     */
    public function getSpamUrl()
    {
        if (isset($this->spam['url'])) {
            return $this->spam['url'];
        }
    }

    /**
     * @param array $spam
     */
    private function setSpam($spam)
    {
        $this->spam = $spam;
    }

    /**
     * @return string|null
     */
    public function getUnsubscribeUrl()
    {
        if (isset($this->unsubscribe['url'])) {
            return $this->unsubscribe['url'];
        }
    }

    /**
     * @param array $unsubscribe
     */
    private function setUnsubscribe($unsubscribe)
    {
        $this->unsubscribe = $unsubscribe;
    }

    /**
     * @return string|null
     */
    public function getClickUrl()
    {
        if (isset($this->click['url'])) {
            return $this->click['url'];
        }
    }

    /**
     * @param array $click
     */
    private function setClick($click)
    {
        $this->click = $click;
    }

    /**
     * @return string|null
     */
    public function getOpenUrl()
    {
        if (isset($this->open['url'])) {
            return $this->open['url'];
        }
    }

    /**
     * @param array $open
     */
    private function setOpen($open)
    {
        $this->open = $open;
    }
}
