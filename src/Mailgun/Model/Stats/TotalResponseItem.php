<?php

/*
 * Copyright (C) 2013 Mailgun
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
     * @var array
     */
    private $complained;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        return new self(
            isset($data['time']) ? new \DateTime($data['time']) : null,
            isset($data['accepted']) ? $data['accepted'] : [],
            isset($data['delivered']) ? $data['delivered'] : [],
            isset($data['failed']) ? $data['failed'] : [],
            isset($data['complained']) ? $data['complained'] : []
        );
    }

    /**
     * @param \DateTime $time
     * @param array     $accepted
     * @param array     $delivered
     * @param array     $failed
     * @param array     $complained
     */
    private function __construct(\DateTime $time, array $accepted, array $delivered, array $failed, array $complained)
    {
        $this->time = $time;
        $this->accepted = $accepted;
        $this->delivered = $delivered;
        $this->failed = $failed;
        $this->complained = $complained;
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

    /**
     * @return array
     */
    public function getComplained()
    {
        return $this->complained;
    }
}
