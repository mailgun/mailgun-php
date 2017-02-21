<?php

/*
 * Copyright (C) 2013-2016 Mailgun
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
    /**
     * @var string
     */
    private $tag;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $resolution;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @var array
     */
    private $stats;

    /**
     * @param string    $tag
     * @param string    $description
     * @param \DateTime $start
     * @param \DateTime $end
     * @param string    $resolution
     * @param array     $stats
     */
    private function __construct($tag, $description, \DateTime $start, \DateTime $end, $resolution, array $stats)
    {
        $this->tag = $tag;
        $this->description = $description;
        $this->resolution = $resolution;
        $this->start = $start;
        $this->end = $end;
        $this->stats = $stats;
    }

    /**
     * @param array $data
     *
     * @return StatisticsResponse
     */
    public static function create(array $data)
    {
        return new self(
            $data['tag'],
            $data['description'],
            new \DateTime($data['start']),
            new \DateTime($data['end']),
            $data['resolution'],
            $data['stats']
        );
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

    /**
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
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
     * @return array
     */
    public function getStats()
    {
        return $this->stats;
    }
}
