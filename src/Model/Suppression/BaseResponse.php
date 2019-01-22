<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
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
    private $address;
    private $message;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $baseResponse = new static();
        $baseResponse->address = isset($data['address']) ? $data['address'] : '';
        $baseResponse->message = isset($data['message']) ? $data['message'] : '';

        return $baseResponse;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
