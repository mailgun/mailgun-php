<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\EmailValidation;

use Mailgun\Model\ApiResponse;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class ParseResponse implements ApiResponse
{
    /**
     * @var array
     */
    private $parsed;

    /**
     * @var array
     */
    private $unparseable;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->parsed = (isset($data['parsed']) && is_array($data['parsed'])) ? $data['parsed'] : [];
        $model->unparseable = (isset($data['unparseable']) && is_array($data['unparseable'])) ? $data['unparseable'] : [];

        return $model;
    }

    public function getParsed(): array
    {
        return $this->parsed;
    }

    public function getUnparseable(): array
    {
        return $this->unparseable;
    }
}
