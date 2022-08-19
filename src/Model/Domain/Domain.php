<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
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
    private $createdAt;
    private $smtpLogin;
    private $name;
    private $smtpPassword;
    private $wildcard;
    private $spamAction;
    private $state;
    private $webScheme;

    public static function create(array $data): self
    {
        $model = new self();
        $model->name = $data['name'] ?? null;
        $model->smtpLogin = $data['smtp_login'] ?? null;
        $model->smtpPassword = $data['smtp_password'] ?? null;
        $model->wildcard = $data['wildcard'] ?? null;
        $model->spamAction = $data['spam_action'] ?? null;
        $model->state = $data['state'] ?? null;
        $model->createdAt = isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null;
        $model->webScheme = $data['web_scheme'] ?? null;

        return $model;
    }

    private function __construct()
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSmtpUsername(): ?string
    {
        return $this->smtpLogin;
    }

    public function getSmtpPassword(): ?string
    {
        return $this->smtpPassword;
    }

    public function isWildcard(): ?bool
    {
        return $this->wildcard;
    }

    public function getSpamAction(): ?string
    {
        return $this->spamAction;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
