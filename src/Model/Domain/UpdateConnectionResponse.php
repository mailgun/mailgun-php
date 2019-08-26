<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Domain;

use Mailgun\Model\ApiResponse;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
final class UpdateConnectionResponse implements ApiResponse
{
    private $message;
    private $noVerify;
    private $requireTLS;

    public static function create(array $data): self
    {
        $model = new self();
        $model->message = $data['message'] ?? null;
        $model->noVerify = $data['skip-verification'] ?? null;
        $model->requireTLS = $data['require-tls'] ?? null;

        return $model;
    }

    private function __construct()
    {
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getSkipVerification(): ?bool
    {
        return $this->noVerify;
    }

    public function getRequireTLS(): ?bool
    {
        return $this->requireTLS;
    }
}
