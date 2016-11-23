<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Domain;

use Mailgun\Assert;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
final class CredentialResponseItem
{
    /**
     * @var int|null
     */
    private $sizeBytes;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $mailbox;

    /**
     * @var string
     */
    private $login;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        Assert::keyExists($data, 'created_at');
        Assert::keyExists($data, 'mailbox');
        Assert::keyExists($data, 'login');

        $sizeBytes = array_key_exists('size_bytes', $data) ? $data['size_bytes'] : null;
        $createdAt = new \DateTime($data['created_at']);
        $mailbox = $data['mailbox'];
        $login = $data['login'];

        Assert::nullOrInteger($sizeBytes);
        Assert::isInstanceOf($createdAt, '\DateTime');
        Assert::string($mailbox);
        Assert::string($login);

        return new self(
            $sizeBytes,
            $createdAt,
            $mailbox,
            $login
        );
    }

    /**
     * @param int       $sizeBytes
     * @param \DateTime $createdAt
     * @param string    $mailbox
     * @param string    $login
     */
    private function __construct($sizeBytes, \DateTime $createdAt, $mailbox, $login)
    {
        $this->sizeBytes = $sizeBytes;
        $this->createdAt = $createdAt;
        $this->mailbox = $mailbox;
        $this->login = $login;
    }

    /**
     * @return int|null
     */
    public function getSizeBytes()
    {
        return $this->sizeBytes;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getMailbox()
    {
        return $this->mailbox;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }
}
