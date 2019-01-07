<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Suppression\Unsubscribe;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class Unsubscribe
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
     * @var array
     */
    private $tags = [];

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
     * @return Unsubscribe
     */
    public static function create(array $data)
    {
        $unsubscribe = new self($data['address']);

        if (isset($data['tags'])) {
            $unsubscribe->setTags($data['tags']);
        }
        if (isset($data['created_at'])) {
            $unsubscribe->setCreatedAt(new \DateTime($data['created_at']));
        }

        return $unsubscribe;
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

    /**
     * @param array $tags
     */
    private function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }
}
