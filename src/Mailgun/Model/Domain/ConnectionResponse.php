<?php

/*
 * Copyright (C) 2013-2016 Mailgun
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
    /**
     * @var bool
     */
    private $noVerify;

    /**
     * @var bool
     */
    private $requireTLS;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        if (!isset($data['connection'])) {
            return;
        }
        $connSettings = $data['connection'];

        return new self(
            isset($connSettings['skip_verification']) ? $connSettings['skip_verification'] : null,
            isset($connSettings['require_tls']) ? $connSettings['require_tls'] : null
        );
    }

    /**
     * @param bool $noVerify   Disable remote TLS certificate verification
     * @param bool $requireTLS Requires TLS for all outbound communication
     */
    private function __construct($noVerify, $requireTLS)
    {
        $this->noVerify = $noVerify;
        $this->requireTLS = $requireTLS;
    }

    /**
     * @return bool
     */
    public function getSkipVerification()
    {
        return $this->noVerify;
    }

    /**
     * @return bool
     */
    public function getRequireTLS()
    {
        return $this->requireTLS;
    }
}
