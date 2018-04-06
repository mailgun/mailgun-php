<?php
namespace Mailgun\Model\MailingList;

use Mailgun\Model\ApiResponse;

/**
 * @author Michael Münch <helmchen@sounds-like.me>
 */
final class ShowResponse implements ApiResponse
{
    /**
     * @var MailingList
     */
    private $list;


    public static function create(array $data)
    {
        $list = null;

        if (isset($data['list'])) {
            $list = MailingList::create($data['list']);
        }

        return new self($list);
    }

    public function __construct(MailingList $list)
    {
        $this->list = $list;
    }

    /**
     * @return MailingList
     */
    public function getList()
    {
        return $this->list;
    }
}