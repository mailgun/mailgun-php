<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList\Member;

use Mailgun\Model\ApiResponse;

final class ShowResponse implements ApiResponse
{
    private $member;

    public static function create(array $data): self
    {
        $model = new self();
        $model->member = Member::create($data['member']);

        return $model;
    }

    private function __construct()
    {
    }

    public function getMember(): Member
    {
        return $this->member;
    }
}
