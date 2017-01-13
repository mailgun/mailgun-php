<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Suppressions\Bounce;

use Mailgun\Resource\ApiResponse;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class ShowResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $error;

    /**
     * @var string
     */
    private $created_at;

    /**
     * @param string $address
     * @param string $code
     * @param string $error
     */
    public function __construct($address)
    {
        $this->address = $address;
        $this->created_at = new \DateTime().format(\DateTime::COOKIE);
    }

    /**
     * @param array $data
     *
     * @return Bounce
     */
    public static function create(array $data)
    {
        $bounce = new self($data['address']);

        if (isset($data['code'])) {
            $this->setCode($data['code']);
        }
        if (isset($data['error'])) {
            $this->setError($data['error']);
        }
        if (isset($data['created_at'])) {
            $this->setCreatedAt($data['created_at']);
        }

        return $bounce;
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError(string $error)
    {
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt(string $created_at)
    {
        $this->created_at = $created_at;
    }
}
