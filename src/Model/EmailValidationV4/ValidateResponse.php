<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\EmailValidationV4;

use Mailgun\Model\ApiResponse;

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
    private $isDisposableAddress = false;

    /**
     * @var bool
     */
    private $isRoleAddress = false;

    /**
     * @var array
     */
    private $reason = [];

    /**
     * @var string|null
     */
    private $result;

    /**
     * @var string|null
     */
    private $risk;

    /**
     * @var string|null
     */
    private $rootAddress;

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
        $model->reason = $data['reason'] ?? [];
        $model->result = $data['result'] ?? null;
        $model->risk = $data['risk'] ?? null;
        $model->rootAddress = $data['root_address'] ?? null;

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

    public function getReason(): array
    {
        return $this->reason;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function getRisk(): ?string
    {
        return $this->risk;
    }

    public function getRootAddress(): ?string
    {
        return $this->rootAddress;
    }
}
