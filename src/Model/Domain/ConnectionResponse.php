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
final class ConnectionResponse implements ApiResponse
{
    private $noVerify;
    private $requireTLS;

    public static function create(array $data): ?self
    {
        if (!isset($data['connection'])) {
            return null;
        }
        $connSettings = $data['connection'];

        $model = new self();
        $model->noVerify = $connSettings['skip_verification'] ?? null;
        $model->requireTLS = $connSettings['require_tls'] ?? null;

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * Disable remote TLS certificate verification.
     *
     * @return bool
     */
    public function getSkipVerification(): ?bool
    {
        return $this->noVerify;
    }

    /**
     * Requires TLS for all outbound communication.
     *
     * @return bool
     */
    public function getRequireTLS(): ?bool
    {
        return $this->requireTLS;
    }
}
