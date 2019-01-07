<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Complaint;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class Complaint
{
    /**
     * @var string
     */
    private $address;

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
     * @return Complaint
     */
    public static function create(array $data)
    {
        $complaint = new self($data['address']);

        if (isset($data['created_at'])) {
            $complaint->setCreatedAt(new \DateTime($data['created_at']));
        }

        return $complaint;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
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
