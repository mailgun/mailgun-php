<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Route\Response;

use Mailgun\Model\ApiResponse;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class DeleteResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $error;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        return new self(
            isset($data['message']) ? $data['message'] : null,
            isset($data['error']) ? $data['error'] : null
        );
    }

    /**
     * @param string $message
     * @param string $error
     */
    private function __construct($message, $error)
    {
        $this->message = $message;
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}
