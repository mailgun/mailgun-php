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

final class CreateResponse implements ApiResponse
{
    private $message;
    private $list;

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

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getList(): MailingList
    {
        return $this->list;
    }
}
