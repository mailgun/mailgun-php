<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Tag;

class Tag
{
    private $tag;
    private $description;
    private $firstSeen;
    private $lastSeen;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();

        $model->tag = $data['tag'] ?? '';
        $model->description = $data['description'] ?? '';
        $model->firstSeen = isset($data['first-seen']) ? new \DateTimeImmutable($data['first-seen']) : null;
        $model->lastSeen = isset($data['last-seen']) ? new \DateTimeImmutable($data['last-seen']) : null;

        return $model;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getFirstSeen(): ?\DateTimeImmutable
    {
        return $this->firstSeen;
    }

    public function getLastSeen(): ?\DateTimeImmutable
    {
        return $this->lastSeen;
    }
}
