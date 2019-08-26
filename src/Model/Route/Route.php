<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Route;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class Route
{
    private $id;
    private $priority;
    private $filter;
    private $actions;
    private $description;
    private $createdAt;

    public static function create(array $data): self
    {
        $model = new self();
        $model->id = $data['id'] ?? null;
        $model->priority = $data['priority'] ?? null;
        $model->filter = $data['expression'] ?? null;
        $model->actions = Action::createMultiple($data['actions'] ?? []);
        $model->description = $data['description'] ?? null;
        $model->createdAt = isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null;

        return $model;
    }

    private function __construct()
    {
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Action[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getFilter(): ?string
    {
        return $this->filter;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
