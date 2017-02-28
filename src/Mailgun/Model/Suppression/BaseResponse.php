<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression;

use Mailgun\Model\ApiResponse;

/**
 * Serves only as an abstract base for Suppression API code.
 *
 * @author Sean Johnson <sean@mailgun.com>
 */
abstract class BaseResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $message;

    /**
     * @param string $address
     * @param string $message
     */
    private function __construct($address, $message)
    {
        $this->address = $address;
        $this->message = $message;
    }

    /**
     * @param array $data
     *
     * @return BaseResponse
     */
    public static function create(array $data)
    {
        $address = isset($data['address']) ? $data['address'] : '';
        $message = isset($data['message']) ? $data['message'] : '';

        return new static($address, $message);
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
