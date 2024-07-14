<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Stats;

use Mailgun\Model\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class TotalResponse implements ApiResponse
{
    private $start;
    private $end;
    private $resolution;
    private $stats;

    private function __construct()
    {
    }

    /**
     * @param array $data
     * @return self
     * @throws \Exception
     */
    public static function create(array $data): self
    {
        $stats = [];
        if (isset($data['stats'])) {
            foreach ($data['stats'] as $s) {
                $stats[] = TotalResponseItem::create($s);
            }
        }

        $model = new self();
        $model->start = isset($data['start']) ? new \DateTimeImmutable($data['start']) : null;
        $model->end = isset($data['end']) ? new \DateTimeImmutable($data['end']) : null;
        $model->resolution = $data['resolution'] ?? null;
        $model->stats = $stats;

        return $model;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getStart(): ?\DateTimeImmutable
    {
        return $this->start;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getEnd(): ?\DateTimeImmutable
    {
        return $this->end;
    }

    /**
     * @return string|null
     */
    public function getResolution(): ?string
    {
        return $this->resolution;
    }

    /**
     * @return TotalResponseItem[]
     */
    public function getStats(): array
    {
        return $this->stats;
    }
}
