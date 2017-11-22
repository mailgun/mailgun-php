<?php

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
    /**
     * @var string
     */
    private $message;

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
        $message = isset($data['message']) ? $data['message'] : null;
        $noVerify = isset($data['skip_verification']) ? $data['skip_verification'] : null;
        $requireTLS = isset($data['require_tls']) ? $data['require_tls'] : null;

        return new self($message, $noVerify, $requireTLS);
    }

    /**
     * @param string $message
     * @param bool   $noVerify
     * @param bool   $requireTLS
     */
    private function __construct($message, $noVerify, $requireTLS)
    {
        $this->message = $message;
        $this->noVerify = $noVerify;
        $this->requireTLS = $requireTLS;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
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
