<?php

namespace Mailgun\Resource\Api\Stats;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class AllResponse
{
    /**
     * @var int
     */
    private $totalCount;

    /**
     * @var Item[]
     */
    private $items;

    /**
     * @param int    $totalCount
     * @param Item[] $items
     */
    public function __construct($totalCount, array $items)
    {
        $this->totalCount = $totalCount;
        $this->items = $items;
    }

    /**
     * @param array $data
     *
     * @return TotalResponse
     */
    public static function createFromArray(array $data)
    {
        $items = [];
        foreach ($data['items'] as $i) {
            $items[] = new Item($i['id'], $i['event'], $i['total_count'], $i['tags'], new \DateTime($i['created_at']));
        }

        return new self($data['total_count'],  $items);
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
