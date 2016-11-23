<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Domain;

use Mailgun\Resource\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class UpdateCredentialResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @param string $message
     */
    private function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        return new self($data['message']);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
