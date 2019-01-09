<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\EmailValidation\Response;

use Mailgun\Model\ApiResponse;
use Mailgun\Model\EmailValidation\Parse;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class ParseResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var Parse
     */
    private $parse;

    /**
     * {@inheritdoc}
     */
    public static function create(array $data)
    {
        $message = isset($data['message']) ? $data['message'] : null;
        $parse = Parse::create($data);

        return new self($message, $parse);
    }

    /**
     * ParseResponse Private Constructor.
     *
     * @param string|null $message
     * @param Parse|null  $parse
     */
    private function __construct($message = null, Parse $parse = null)
    {
        $this->message = $message;
        $this->parse = $parse;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return Parse
     */
    public function getParse()
    {
        return $this->parse;
    }
}
