<?php

/**
 * Copyright (C) 2013-2016 Mailgun.
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */
namespace Mailgun\Resource\Api;

use Mailgun\Assert;
use Mailgun\Resource\CreatableFromArray;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class SimpleResponse implements CreatableFromArray
{
    /**
     * @var string
     */
    private $message;

    /**
     * Only set when API rate limit is hit and a rate limit response is returned.
     *
     * @var int
     */
    private $retrySeconds = null;

    /**
     * Only set on calls such as DELETE /v3/domains/.../credentials/<user>.
     *
     * @var string
     */
    private $spec = null;

    /**
     * @param array $data
     *
     * @return SimpleResponse
     */
    public static function createFromArray(array $data)
    {
        $message = array_key_exists('message', $data) ? $data['message'] : null;
        $retrySeconds = array_key_exists('retry_seconds', $data) ? $data['retry_seconds'] : null;
        $spec = array_key_exists('spec', $data) ? $data['spec'] : null;

        return new static($message, $retrySeconds, $spec);
    }

    /**
     * @param string|null $message
     * @param int|null    $retrySeconds
     * @param string|null $spec
     */
    public function __construct($message, $retrySeconds, $spec)
    {
        Assert::nullOrString($message);
        Assert::nullOrInteger($retrySeconds);
        Assert::nullOrString($spec);

        $this->message = $message;
        $this->retrySeconds = $retrySeconds;
        $this->spec = $spec;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getSpec()
    {
        return $this->spec;
    }

    /**
     * @return bool
     */
    public function isRateLimited()
    {
        return null !== $this->retrySeconds;
    }

    /**
     * @return int
     */
    public function getRetrySeconds()
    {
        return $this->retrySeconds;
    }
}
