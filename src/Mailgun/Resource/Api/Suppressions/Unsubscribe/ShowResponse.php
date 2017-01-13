<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Suppressions\Unsubscribe;

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
    private $tag;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @param string $address
     */
    public function __construct($address)
    {
        $this->address = $address;
        $this->created_at = new \DateTime();
    }

    /**
     * @param array $data
     *
     * @return Unsubscribe
     */
    public static function create(array $data)
    {
        $unsubscribe = new self($data['address']);

        if (isset($data['tag'])) {
            $this->setTag($data['tag']);
        }
        if (isset($data['created_at'])) {
            $this->setCreatedAt(new \DateTime($data['created_at']));
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
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    private function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     */
    private function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
}

