<?php
namespace Mailgun\Model\MailingList;

use Mailgun\Model\ApiResponse;

/**
 * @author Michael Münch <helmchen@sounds-like.me>
 */
final class ShowMemberResponse implements ApiResponse
{
    /**
     * @var Member
     */
    private $member;


    public static function create(array $data)
    {
        $member = null;

        if (isset($data['member'])) {
            $member = Member::create($data['member']);
        }

        return new self($member);
    }

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * @return Member
     */
    public function getMember()
    {
        return $this->member;
    }
}