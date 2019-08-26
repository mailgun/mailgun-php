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
 * @author Sean Johnson <sean@mailgun.com>
 */
final class CredentialResponseItem
{
    private $sizeBytes;
    private $createdAt;
    private $mailbox;
    private $login;

    public static function create(array $data): self
    {
        $model = new self();
        $model->sizeBytes = $data['size_bytes'] ?? null;
        $model->createdAt = isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null;
        $model->mailbox = $data['mailbox'] ?? null;
        $model->login = $data['login'] ?? null;

        return $model;
    }

    private function __construct()
    {
    }

    public function getSizeBytes(): ?int
    {
        return $this->sizeBytes;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getMailbox(): ?string
    {
        return $this->mailbox;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }
}
