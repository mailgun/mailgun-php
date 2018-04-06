<?php
namespace Mailgun\Model\MailingList;

use Mailgun\Model\ApiResponse;

/**
 * @author Michael Münch <helmchen@sounds-like.me>
 */
final class PagesResponse implements ApiResponse
{
    /**
     * @var MailingList[]
     */
    private $items;

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
                $items[] = MailingList::create($item);
            }
        }

        $paging = $data['paging'] ?? [];

        return new self($items, $paging);
    }

    /**
     * @param MailingList[] $items
     * @param array $paging
     */
    private function __construct(array $items, array $paging)
    {
        $this->items = $items;
        $this->paging = $paging;
    }

    /**
     * @return MailingList[]
     */
    public function getLists()
    {
        return $this->items;
    }

    public function getPaging()
    {
        return $this->paging;
    }
}
