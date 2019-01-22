<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\EmailValidation;

use Mailgun\Model\ApiResponse;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class ValidateResponse implements ApiResponse
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

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->address = $data['address'] ?? null;
        $model->didYouMean = $data['did_you_mean'] ?? null;
        $model->isDisposableAddress = $data['is_disposable_address'] ?? false;
        $model->isRoleAddress = $data['is_role_address'] ?? false;
        $model->isValid = $data['is_valid'] ?? false;
        $model->mailboxVerification = isset($data['mailbox_verification']) ? 'true' === $data['mailbox_verification'] : false;
        $model->parts = Parts::create($data['parts'] ?? []);
        $model->reason = $data['reason'] ?? null;

        return $model;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getDidYouMean(): ?string
    {
        return $this->didYouMean;
    }

    public function isDisposableAddress(): bool
    {
        return $this->isDisposableAddress;
    }

    public function isRoleAddress(): bool
    {
        return $this->isRoleAddress;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function isMailboxVerification(): bool
    {
        return $this->mailboxVerification;
    }

    public function getParts(): Parts
    {
        return $this->parts;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }
}
