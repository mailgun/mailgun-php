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
 * This is only mean to be the base response for Webhook API.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
abstract class BaseResponse implements ApiResponse
{
    /**
     * @var array
     */
    private $webhook = [];

    /**
     * @var string
     */
    private $message;

    /**
     * @param array  $webhook
     * @param string $message
     */
    public function __construct(array $webhook, $message)
    {
        $this->webhook = $webhook;
        $this->message = $message;
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function create(array $data)
    {
        $webhook = [];
        $message = '';
        if (isset($data['webhook'])) {
            $webhook = $data['webhook'];
        }

        if (isset($data['message'])) {
            $message = $data['message'];
        }

        return new static($webhook, $message);
    }

    /**
     * @return string|null
     */
    public function getWebhookUrl()
    {
        if (isset($this->webhook['url'])) {
            return $this->webhook['url'];
        }
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
