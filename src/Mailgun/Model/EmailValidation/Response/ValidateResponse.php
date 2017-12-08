<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\EmailValidation\Response;

use Mailgun\Model\ApiResponse;
use Mailgun\Model\EmailValidation\EmailValidation;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class ValidateResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var EmailValidation
     */
    private $emailValidation;

    /**
     * {@inheritdoc}
     */
    public static function create(array $data)
    {
        $message = isset($data['message']) ? $data['message'] : null;
        $route = isset($data['route']) ? EmailValidation::create($data['emailValidation']) : null;

        return new self($message, $route);
    }

    /**
     * CreateResponse Private Constructor.
     *
     * @param string|null          $message
     * @param EmailValidation|null $emailValidation
     */
    private function __construct($message = null, EmailValidation $emailValidation = null)
    {
        $this->message = $message;
        $this->emailValidation = $emailValidation;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return EmailValidation
     */
    public function getEmailValidation()
    {
        return $this->emailValidation;
    }
}
