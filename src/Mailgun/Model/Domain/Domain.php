<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Domain;

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
        return new self(
            isset($data['name']) ? $data['name'] : null,
            isset($data['smtp_login']) ? $data['smtp_login'] : null,
            isset($data['smtp_password']) ? $data['smtp_password'] : null,
            isset($data['wildcard']) ? $data['wildcard'] : null,
            isset($data['spam_action']) ? $data['spam_action'] : null,
            isset($data['state']) ? $data['state'] : null,
            isset($data['created_at']) ? new \DateTime($data['created_at']) : null
        );
    }

    /**
     * @param string    $name
     * @param string    $smtpLogin
     * @param string    $smtpPassword
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
