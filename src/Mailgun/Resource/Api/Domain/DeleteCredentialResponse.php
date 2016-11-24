<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */


namespace Mailgun\Resource\Api\Domain;

use Mailgun\Resource\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class DeleteCredentialResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var error
     */
    private $error;

    /**
     * @var string
     */
    private $spec;

    /**
     * @param string $message
     */
    private function __construct($message, $error, $spec)
    {
        $this->message = $message;
        $this->error = $error;
        $this->spec = $spec;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        return new self(
            isset($data['message']) ? $data['message'] : null,
            isset($data['error']) ? $data['error'] : null,
            isset($data['spec']) ? $data['spec'] : null
        );
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
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getSpec()
    {
        return $this->spec;
    }
}
