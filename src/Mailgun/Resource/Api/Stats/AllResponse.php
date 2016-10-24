<?php

namespace Mailgun\Resource\Api\Stats;

use Mailgun\Resource\CreatableFromArray;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class AllResponse implements CreatableFromArray
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
     * @return AllResponse
     */
    public static function createFromArray(array $data)
    {
        $items = [];
        foreach ($data['items'] as $i) {
            $items[] = new Item($i['id'], $i['event'], $i['total_count'], $i['tags'], new \DateTime($i['created_at']));
        }

        return new self($data['total_count'], $items);
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
