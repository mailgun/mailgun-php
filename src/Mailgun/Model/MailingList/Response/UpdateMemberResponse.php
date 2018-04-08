<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList\Response;

use Mailgun\Model\MailingList\Member;
use Mailgun\Model\ApiResponse;

/**
 * @author Michael Münch <helmchen@sounds-like.me>
 */
final class UpdateMemberResponse implements ApiResponse
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
        $member = null;

        if (isset($data['member'])) {
            $member = Member::create($data['member']);
        }

        $message = null;

        if (isset($data['message'])) {
            $message = $data['message'];
        }

        return new self($member, $message);
    }

    /**
     * UpdateMemberResponse constructor.
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
