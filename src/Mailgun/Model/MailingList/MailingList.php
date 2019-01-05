<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList;

use Mailgun\Model\ApiResponse;

final class MailingList implements ApiResponse
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $accessLevel;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $membersCount;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        return new self(
            isset($data['name']) ? $data['name'] : null,
            isset($data['address']) ? $data['address'] : null,
            isset($data['access_level']) ? $data['access_level'] : null,
            isset($data['description']) ? $data['description'] : null,
            isset($data['members_count']) ? $data['members_count'] : null,
            isset($data['created_at']) ? new \DateTime($data['created_at']) : null
        );
    }

    /**
     * MailingList constructor.
     *
     * @param string    $name
     * @param string    $address
     * @param string    $accessLevel
     * @param string    $description
     * @param int       $membersCount
     * @param \DateTime $createdAt
     */
    private function __construct($name, $address, $accessLevel, $description, $membersCount, \DateTime $createdAt)
    {
        $this->name = $name;
        $this->address = $address;
        $this->accessLevel = $accessLevel;
        $this->description = $description;
        $this->membersCount = $membersCount;
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function getAccessLevel()
    {
        return $this->accessLevel;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getMembersCount()
    {
        return $this->membersCount;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
