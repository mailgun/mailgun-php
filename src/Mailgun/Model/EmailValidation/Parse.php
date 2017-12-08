<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\EmailValidation;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class Parse
{
    /**
     * @var array
     */
    private $parsed;

    /**
     * @var array
     */
    private $unparseable;

    /**
     * Parse constructor.
     *
     * @param array $parsed
     * @param array $unparseable
     */
    private function __construct(array $parsed, array $unparseable)
    {
        $this->parsed = $parsed;
        $this->unparseable = $unparseable;
    }

    /**
     * @param array $data
     *
     * @return Parse
     */
    public static function create(array $data)
    {
        return new self(
            ((isset($data['parsed']) && is_array($data['parsed'])) ? $data['parsed'] : []),
            ((isset($data['unparseable']) && is_array($data['unparseable'])) ? $data['unparseable'] : [])
        );
    }

    /**
     * @return array
     */
    public function getParsed()
    {
        return $this->parsed;
    }

    /**
     * @return array
     */
    public function getUnparseable()
    {
        return $this->unparseable;
    }
}
