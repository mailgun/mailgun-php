<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Stats;

use Mailgun\Assert;

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
        Assert::string($data['time']);
        Assert::isArray($data['accepted']);
        Assert::isArray($data['delivered']);
        Assert::isArray($data['failed']);

        return new self(new \DateTime($data['time']), $data['accepted'], $data['delivered'], $data['failed']);
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
