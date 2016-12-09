<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Routes\Dto;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class Route
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var string
     */
    private $filter;

    /**
     * @var Action[]
     */
    private $actions;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * Route Named Constructor.
     *
     * @param array $data
     *
     * @return Route
     */
    public static function create(array $data)
    {
        return new self(
            isset($data['id']) ? $data['id'] : null,
            isset($data['priority']) ? $data['priority'] : null,
            isset($data['expression']) ? $data['expression'] : null,
            isset($data['actions']) ? $data['actions'] : [],
            isset($data['description']) ? $data['description'] : null,
            isset($data['created_at']) ? new \DateTime($data['created_at']) : null
        );
    }

    /**
     * Route Private Constructor.
     *
     * @param string    $id
     * @param int       $priority
     * @param string    $expression
     * @param array     $actions
     * @param string    $description
     * @param \DateTime $createdAt
     */
    private function __construct($id, $priority, $expression, $actions, $description, \DateTime $createdAt = null)
    {
        $this->id = $id;
        $this->priority = $priority;
        $this->filter = $expression;
        $this->actions = Action::createMultiple($actions);
        $this->description = $description;
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
