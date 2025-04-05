<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Domain;

use DateTimeImmutable;

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
    private ?string $webPrefix;
    private string $type;
    private bool $useAutomaticSenderSecurity;
    private bool $requireTls;
    private bool $skipVerification;
    private string $id;
    private bool $isDisabled;

    /**
     * @param array $data
     * @return self
     */
    public static function create(array $data): self
    {
        $model = new self();
        $model->name = $data['name'] ?? null;
        $model->smtpLogin = $data['smtp_login'] ?? null;
        $model->smtpPassword = $data['smtp_password'] ?? null;
        $model->wildcard = $data['wildcard'] ?? null;
        $model->spamAction = $data['spam_action'] ?? null;
        $model->state = $data['state'] ?? null;
        $model->createdAt = isset($data['created_at']) ? new DateTimeImmutable($data['created_at']) : null;
        $model->webScheme = $data['web_scheme'] ?? null;
        $model->webPrefix = $data['web_prefix'] ?? null;
        $model->type = $data['type'] ?? 'sandbox';
        $model->useAutomaticSenderSecurity = $data['use_automatic_sender_security'] ?? false;
        $model->requireTls = $data['require_tls'] ?? false;
        $model->skipVerification = $data['skip_verification'] ?? false;
        $model->id = $data['id'] ?? '';
        $model->isDisabled = $data['is_disabled'] ?? false;

        return $model;
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
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
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

    /**
     * @return string|null
     */
    public function getWebPrefix(): ?string
    {
        return $this->webPrefix;
    }

    /**
     * @param string|null $webPrefix
     */
    public function setWebPrefix(?string $webPrefix): void
    {
        $this->webPrefix = $webPrefix;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isUseAutomaticSenderSecurity(): bool
    {
        return $this->useAutomaticSenderSecurity;
    }

    /**
     * @param bool $useAutomaticSenderSecurity
     */
    public function setUseAutomaticSenderSecurity(bool $useAutomaticSenderSecurity): void
    {
        $this->useAutomaticSenderSecurity = $useAutomaticSenderSecurity;
    }

    /**
     * @return bool
     */
    public function isRequireTls(): bool
    {
        return $this->requireTls;
    }

    /**
     * @param bool $requireTls
     */
    public function setRequireTls(bool $requireTls): void
    {
        $this->requireTls = $requireTls;
    }

    /**
     * @return bool
     */
    public function isSkipVerification(): bool
    {
        return $this->skipVerification;
    }

    /**
     * @param bool $skipVerification
     */
    public function setSkipVerification(bool $skipVerification): void
    {
        $this->skipVerification = $skipVerification;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->isDisabled;
    }

    /**
     * @param bool $isDisabled
     */
    public function setIsDisabled(bool $isDisabled): void
    {
        $this->isDisabled = $isDisabled;
    }

    private function __construct()
    {
    }
}
