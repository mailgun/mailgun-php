<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Stats;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class TotalResponseItem
{
    /**
     * @var \DateTime
     */
    private $time;

    /**
     * @var array
     */
    private $accepted;

    /**
     * @var array
     */
    private $delivered;

    /**
     * @var array
     */
    private $failed;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        return new self(
            isset($data['time']) ? new \DateTime($data['time']) : null,
            isset($data['accepted']) ? $data['accepted'] : null,
            isset($data['delivered']) ? $data['delivered'] : null,
            isset($data['failed']) ? $data['failed'] : null
        );
    }

    /**
     * @param \DateTime $time
     * @param array     $accepted
     * @param array     $delivered
     * @param array     $failed
     */
    private function __construct(\DateTime $time, array $accepted, array $delivered, array $failed)
    {
        $this->time = $time;
        $this->accepted = $accepted;
        $this->delivered = $delivered;
        $this->failed = $failed;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return array
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * @return array
     */
    public function getDelivered()
    {
        return $this->delivered;
    }

    /**
     * @return array
     */
    public function getFailed()
    {
        return $this->failed;
    }
}
