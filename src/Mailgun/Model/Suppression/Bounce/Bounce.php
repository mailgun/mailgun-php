<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Bounce;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class Bounce
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
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @param string $address
     */
    private function __construct($address)
    {
        $this->address = $address;
        $this->createdAt = new \DateTime();
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
            $bounce->setCode($data['code']);
        }
        if (isset($data['error'])) {
            $bounce->setError($data['error']);
        }
        if (isset($data['created_at'])) {
            $bounce->setCreatedAt(new \DateTime($data['created_at']));
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
    private function setCode($code)
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
    private function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    private function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
