<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Message;

use Mailgun\Model\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class SendResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * @param string $id
     * @param string $message
     */
    private function __construct($id, $message)
    {
        $this->id = $id;
        $this->message = $message;
    }

    /**
     * @param array $data
     *
     * @return SendResponse
     */
    public static function create(array $data)
    {
        $id = '';
        $message = '';

        if (isset($data['id'])) {
            $id = $data['id'];
        }
        if (isset($data['message'])) {
            $message = $data['message'];
        }

        return new self($id, $message);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
