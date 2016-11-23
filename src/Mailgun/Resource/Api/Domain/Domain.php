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
 * Represents domain information in its simplest form.
 *
 * @author Sean Johnson <sean@ramcloud.io>
 */
final class Domain
{
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $smtpLogin;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $smtpPassword;

    /**
     * @var bool
     */
    private $wildcard;

    /**
     * @var string
     */
    private $spamAction;

    /**
     * @var string
     */
    private $state;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        Assert::keyExists($data, 'name');
        Assert::keyExists($data, 'smtp_login');
        Assert::keyExists($data, 'smtp_password');
        Assert::keyExists($data, 'wildcard');
        Assert::keyExists($data, 'spam_action');
        Assert::keyExists($data, 'state');
        Assert::keyExists($data, 'created_at');

        return new self(
            $data['name'],
            $data['smtp_login'],
            $data['smtp_password'],
            $data['wildcard'],
            $data['spam_action'],
            $data['state'],
            new \DateTime($data['created_at'])
        );
    }

    /**
     * @param string    $name
     * @param string    $smtpLogin
     * @param string    $smtpPass
     * @param bool      $wildcard
     * @param string    $spamAction
     * @param string    $state
     * @param \DateTime $createdAt
     */
    private function __construct($name, $smtpLogin, $smtpPassword, $wildcard, $spamAction, $state, \DateTime $createdAt)
    {
        $this->name = $name;
        $this->smtpLogin = $smtpLogin;
        $this->smtpPassword = $smtpPassword;
        $this->wildcard = $wildcard;
        $this->spamAction = $spamAction;
        $this->state = $state;
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSmtpUsername()
    {
        return $this->smtpLogin;
    }

    /**
     * @return string
     */
    public function getSmtpPassword()
    {
        return $this->smtpPassword;
    }

    /**
     * @return bool
     */
    public function isWildcard()
    {
        return $this->wildcard;
    }

    /**
     * @return string
     */
    public function getSpamAction()
    {
        return $this->spamAction;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
