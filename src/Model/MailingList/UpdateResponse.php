<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList;

use Mailgun\Model\ApiResponse;

final class UpdateResponse implements ApiResponse
{
    private $message;
    private $list;

    /**
     * @param array $data
     * @return self
     */
    public static function create(array $data): self
    {
        $model = new self();
        $model->list = MailingList::create($data['list']);
        $model->message = $data['message'] ?? '';

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return MailingList
     */
    public function getList(): MailingList
    {
        return $this->list;
    }
}
