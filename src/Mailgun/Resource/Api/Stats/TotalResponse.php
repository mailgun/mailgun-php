<?php

namespace Mailgun\Resource\Api\Stats;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class TotalResponse
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
     * @var TotalStats[]
     */
    private $stats;

    /**
     * @param \DateTime    $start
     * @param \DateTime    $end
     * @param string       $resolution
     * @param TotalStats[] $stats
     */
    public function __construct(\DateTime $start, \DateTime $end, $resolution, array $stats)
    {
        $this->start = $start;
        $this->end = $end;
        $this->resolution = $resolution;
        $this->stats = $stats;
    }

    /**
     * @param array $data
     *
     * @return TotalResponse
     */
    public static function createFromArray(array $data)
    {
        $stats = [];
        foreach ($data['stats'] as $s) {
            $stats[] = new TotalStats(new \DateTime($s['time']), $s['accepted'], $s['delivered'], $s['failed']);
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
     * @return TotalStats[]
     */
    public function getStats()
    {
        return $this->stats;
    }
}
