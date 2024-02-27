<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Templates;

class Template
{
    /** @var string $id */
    private $id;
    /** @var string $id */
    private $name;
    /** @var string $id */
    private $description;
    /** @var string $id */
    private $createdAt;
    /** @var string $id */
    private $createdBy;

    /**
     *
     */
    private function __construct()
    {
    }

    /**
     * @param array $data
     * @return static
     */
    public static function create(array $data): self
    {
        $model = new self();

        $model->setId($data['id']);
        $model->setName($data['name']);
        $model->setDescription($data['description'] ?? '');
        $model->setCreatedAt($data['createdAt'] ?? '');
        $model->setCreatedBy($data['createdBy'] ?? '');

        return $model;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    /**
     * @param string $createdBy
     */
    public function setCreatedBy(string $createdBy): void
    {
        $this->createdBy = $createdBy;
    }
}
