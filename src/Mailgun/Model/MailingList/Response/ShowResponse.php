<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList\Response;

use Mailgun\Model\MailingList\MailingList;
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

    private function __construct(MailingList $list)
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
