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
class ShowResponse implements ApiResponse
{
    /**
     * @var array
     */
    private $webhook = [];

    /**
     * @param array $webhook
     */
    public function __construct(array $webhook)
    {
        $this->webhook = $webhook;
    }

    /**
     * @param array $data
     *
     * @return ShowResponse
     */
    public static function create(array $data)
    {
        $webhook = [];
        if (isset($data['webhook'])) {
            $webhook = $data['webhook'];
        }

        return new self($webhook);
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
}
