<?php
namespace Mailgun\Model\MailingList;

use Mailgun\Model\ApiResponse;

/**
 * @author Michael Münch <helmchen@sounds-like.me>
 */
final class MembersResponse implements ApiResponse
{
    /**
     * @var Member[]
     */
    private $items;

    /**
     * @var array
     */
    private $paging;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        $items = [];

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $items[] = Member::create($item);
            }
        }

        $paging = $data['paging'] ?? [];

        return new self($items, $paging);
    }

    /**
     * @param Member[] $items
     * @param array $paging
     */
    private function __construct(array $items, array $paging)
    {
        $this->items = $items;
        $this->paging = $paging;
    }

    /**
     * @return Member[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public function getPaging()
    {
        return $this->paging;
    }
}
