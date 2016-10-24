<?php

namespace Mailgun\Resource\Api\Stats;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class TotalStats
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
     * @param \DateTime $time
     * @param array     $accepted
     * @param array     $delivered
     * @param array     $failed
     */
    public function __construct(\DateTime $time, array $accepted, array $delivered, array $failed)
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
