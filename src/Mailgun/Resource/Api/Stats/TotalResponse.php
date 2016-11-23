<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Stats;

use Mailgun\Resource\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class TotalResponse implements ApiResponse
{
    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var string
     */
    private $resolution;

    /**
     * @var TotalResponseItem[]
     */
    private $stats;

    /**
     * @param \DateTime           $start
     * @param \DateTime           $end
     * @param string              $resolution
     * @param TotalResponseItem[] $stats
     */
    private function __construct(\DateTime $start, \DateTime $end, $resolution, array $stats)
    {
        $this->start = $start;
        $this->end = $end;
        $this->resolution = $resolution;
        $this->stats = $stats;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        $stats = [];
        foreach ($data['stats'] as $s) {
            $stats[] = TotalResponseItem::create($s);
        }

        return new self(new \DateTime($data['start']), new \DateTime($data['end']), $data['resolution'], $stats);
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @return TotalResponseItem[]
     */
    public function getStats()
    {
        return $this->stats;
    }
}
