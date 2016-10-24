<?php

namespace Mailgun\Resource\Api\Stats;

class Item
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
     * @param string    $id
     * @param string    $event
     * @param string    $totalCount
     * @param \string[] $tags
     * @param \DateTime $createdAt
     */
    public function __construct($id, $event, $totalCount, array $tags, \DateTime $createdAt)
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
     * @return \string[]
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
