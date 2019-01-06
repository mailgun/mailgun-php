<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList;

use Mailgun\Model\ApiResponse;

final class DeleteResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $address;

    public static function create(array $data)
    {
        $message = isset($data['message']) ? $data['message'] : '';
        $address = isset($data['address']) ? $data['address'] : '';

        return new self($address, $message);
    }

    /**
     * DeleteResponse constructor.
     *
     * @param string $address
     * @param string $message
     */
    private function __construct($address, $message)
    {
        $this->address = $address;
        $this->message = $message;
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
    public function getAddress()
    {
        return $this->address;
    }
}
