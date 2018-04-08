<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList\Response;

use Mailgun\Model\ApiResponse;

/**
 * @author Michael Münch <helmchen@sounds-like.me>
 */
final class UpdateResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var MailingList
     */
    private $list;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        $message = null;

        if (isset($data['message'])) {
            $message = $data['message'];
        }

        $list = null;

        if (isset($data['list'])) {
            $list = MailingList::create($data['list']);
        }

        return new self($list, $message);
    }

    private function __construct(MailingList $list, $message)
    {
        $this->list = $list;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return MailingList
     */
    public function getList()
    {
        return $this->list;
    }
}
