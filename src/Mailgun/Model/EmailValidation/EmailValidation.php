<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\EmailValidation;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class EmailValidation
{
    /**
     * @var string|null
     */
    private $address;

    /**
     * @var string|null
     */
    private $didYouMean;

    /**
     * @var bool
     */
    private $isDisposableAddress;

    /**
     * @var bool
     */
    private $isRoleAddress;

    /**
     * @var bool
     */
    private $isValid;

    /**
     * @var bool
     */
    private $mailboxVerification;

    /**
     * @var Parts
     */
    private $parts;

    /**
     * @var string|null
     */
    private $reason;

    /**
     * EmailValidation constructor.
     *
     * @param string|null $address
     * @param string|null $didYouMean
     * @param bool        $isDisposableAddress
     * @param bool        $isRoleAddress
     * @param bool        $isValid
     * @param string|null $mailboxVerification
     * @param array       $parts
     * @param string|null $reason
     */
    private function __construct(
        $address,
        $didYouMean,
        $isDisposableAddress,
        $isRoleAddress,
        $isValid,
        $mailboxVerification,
        $parts,
        $reason
    ) {
        $this->address = $address;
        $this->didYouMean = $didYouMean;
        $this->isDisposableAddress = $isDisposableAddress;
        $this->isRoleAddress = $isRoleAddress;
        $this->isValid = $isValid;
        $this->mailboxVerification = 'true' === $mailboxVerification ? true : false;
        $this->parts = Parts::create($parts);
        $this->reason = $reason;
    }

    /**
     * @param array $data
     *
     * @return EmailValidation
     */
    public static function create(array $data)
    {
        return new self(
            (isset($data['address']) ? $data['address'] : null),
            (isset($data['did_you_mean']) ? $data['did_you_mean'] : null),
            (isset($data['is_disposable_address']) ? $data['is_disposable_address'] : false),
            (isset($data['is_role_address']) ? $data['is_role_address'] : false),
            (isset($data['is_valid']) ? $data['is_valid'] : false),
            (isset($data['mailbox_verification']) ? $data['mailbox_verification'] : null),
            (isset($data['parts']) ? $data['parts'] : []),
            (isset($data['reason']) ? $data['reason'] : null)
        );
    }

    /**
     * @return null|string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return null|string
     */
    public function getDidYouMean()
    {
        return $this->didYouMean;
    }

    /**
     * @return bool
     */
    public function isDisposableAddress()
    {
        return $this->isDisposableAddress;
    }

    /**
     * @return bool
     */
    public function isRoleAddress()
    {
        return $this->isRoleAddress;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * @return bool
     */
    public function isMailboxVerification()
    {
        return $this->mailboxVerification;
    }

    /**
     * @return Parts
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * @return null|string
     */
    public function getReason()
    {
        return $this->reason;
    }
}
