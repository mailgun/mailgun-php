<?php

namespace Mailgun\Resource\Api\Tag;

class Tag
{
    /**
     * @var string
     */
    private $tag;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $firstSeen;

    /**
     * @var \DateTime
     */
    private $lastSeen;

    /**
     * @param string    $tag
     * @param string    $description
     * @param \DateTime $firstSeen
     * @param \DateTime $lastSeen
     */
    public function __construct($tag, $description, \DateTime $firstSeen, \DateTime $lastSeen)
    {
        $this->tag = $tag;
        $this->description = $description;
        $this->firstSeen = $firstSeen;
        $this->lastSeen = $lastSeen;
    }

    /**
     * @param array $data
     *
     * @return Tag
     */
    public static function create(array $data)
    {
        return new self($data['tag'], $data['description'], $data['first-seen'], $data['last-seen']);
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
