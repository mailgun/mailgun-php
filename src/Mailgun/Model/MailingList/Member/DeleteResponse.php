<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList\Member;

use Mailgun\Model\ApiResponse;

final class DeleteResponse implements ApiResponse
{
    /**
     * @var Member
     */
    private $member;

    /**
     * @var string
     */
    private $message;

    public static function create(array $data)
    {
        $member = Member::create($data['member']);
        $message = isset($data['message']) ? $data['message'] : '';

        return new self($member, $message);
    }

    /**
     * DeleteMemberResponse constructor.
     *
     * @param Member $member
     * @param string $message
     */
    private function __construct(Member $member, $message)
    {
        $this->member = $member;
        $this->message = $message;
    }

    /**
     * @return Member
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
