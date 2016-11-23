<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Domain;

use Mailgun\Assert;
use Mailgun\Resource\ApiResponse;

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
        Assert::keyExists($data, 'message');
        Assert::keyExists($data, 'skip_verification');
        Assert::keyExists($data, 'require_tls');

        $message = $data['message'];
        $noVerify = $data['skip_verification'];
        $requireTLS = $data['require_tls'];

        Assert::nullOrString($message);
        Assert::boolean($noVerify);
        Assert::boolean($requireTLS);

        return new self(
            $message,
            $noVerify,
            $requireTLS
        );
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
