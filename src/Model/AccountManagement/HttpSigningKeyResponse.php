<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\AccountManagement;

use Mailgun\Model\ApiResponse;

final class HttpSigningKeyResponse implements ApiResponse
{
    private string $key;
    private string $createdAt;
    private string $httpSigningKey;
    private string $message;

    /**
     * @param array $data
     * @return self
     */
    public static function create(array $data): self
    {
        $model = new self();
        $model->key = $data['key'] ?? '';
        $model->createdAt = $data['created_at'] ?? '';
        $model->httpSigningKey = $data['http_signing_key'] ?? '';
        $model->message = $data['message'] ?? '';

        return $model;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getHttpSigningKey(): string
    {
        return $this->httpSigningKey;
    }

    /**
     * @param string $httpSigningKey
     * @return void
     */
    public function setHttpSigningKey(string $httpSigningKey): void
    {
        $this->httpSigningKey = $httpSigningKey;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    private function __construct()
    {
    }
}
