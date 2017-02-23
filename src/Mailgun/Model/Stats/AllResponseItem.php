<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Stats;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class AllResponseItem
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $event;

    /**
     * @var string
     */
    private $totalCount;

    /**
     * @var string[]
     */
    private $tags;

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
            isset($data['id']) ? $data['id'] : null,
            isset($data['event']) ? $data['event'] : null,
            isset($data['total_count']) ? $data['total_count'] : null,
            isset($data['tags']) ? $data['tags'] : null,
            isset($data['created_at']) ? new \DateTime($data['created_at']) : null
        );
    }

    /**
     * @param string    $id
     * @param string    $event
     * @param string    $totalCount
     * @param \string[] $tags
     * @param \DateTime $createdAt
     */
    private function __construct($id, $event, $totalCount, array $tags, \DateTime $createdAt)
    {
        $this->id = $id;
        $this->event = $event;
        $this->totalCount = $totalCount;
        $this->tags = $tags;
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return string
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @return string[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
