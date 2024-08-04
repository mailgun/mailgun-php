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
    private ?string $smtpLogin;
    private ?string $name;
    private ?string $smtpPassword;
    private $wildcard;
    private ?string $spamAction;
    private ?string $state;
    private ?string $webScheme;

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

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getSmtpUsername(): ?string
    {
        return $this->smtpLogin;
    }

    /**
     * @return string|null
     */
    public function getSmtpPassword(): ?string
    {
        return $this->smtpPassword;
    }

    /**
     * @return bool|null
     */
    public function isWildcard(): ?bool
    {
        return $this->wildcard;
    }

    /**
     * @return string|null
     */
    public function getSpamAction(): ?string
    {
        return $this->spamAction;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return string|null
     */
    public function getWebScheme(): ?string
    {
        return $this->webScheme;
    }

    /**
     * @param string|null $webScheme
     * @return void
     */
    public function setWebScheme(?string $webScheme): void
    {
        $this->webScheme = $webScheme;
    }
}
