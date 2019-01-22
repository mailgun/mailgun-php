<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Tag;

use Mailgun\Model\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class StatisticsResponse implements ApiResponse
{
    private $tag;
    private $description;
    private $resolution;
    private $start;
    private $end;
    private $stats;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();

        $model->tag = $data['tag'] ?? '';
        $model->description = $data['description'] ?? '';
        $model->resolution = $data['resolution'] ?? null;
        $model->stats = $data['stats'] ?? [];
        $model->start = isset($data['start']) ? new \DateTimeImmutable($data['start']) : null;
        $model->end = isset($data['end']) ? new \DateTimeImmutable($data['end']) : null;

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

    public function getResolution(): ?string
    {
        return $this->resolution;
    }

    public function getStart(): ?\DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): ?\DateTimeImmutable
    {
        return $this->end;
    }

    public function getStats(): array
    {
        return $this->stats;
    }
}
