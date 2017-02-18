<?php

namespace Mailgun\Resource\Api\Tag;


use Mailgun\Resource\Api\PaginationResponse;
use Mailgun\Resource\ApiResponse;

class IndexResponse implements ApiResponse
{
    use PaginationResponse;

    /**
     * @var Tag[]
     */
    private $items;

    /**
     * @param Tag[] $items
     * @param array   $paging
     */
    public function __construct(array $items, array $paging)
    {
        $this->items = $items;
        $this->paging = $paging;
    }

    public static function create(array $data)
    {
        $items = [];
        foreach ($data['items'] as $item) {
            $items[] = Tag::create($item);
        }

        return new self($items, $data['paging']);
    }

    /**
     * @return Tag[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
