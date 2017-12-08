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
final class Parts
{
    /**
     * @var string|null
     */
    private $displayName;

    /**
     * @var string|null
     */
    private $domain;

    /**
     * @var string|null
     */
    private $localPart;

    /**
     * Parts constructor.
     *
     * @param string|null $displayName
     * @param string|null $domain
     * @param string|null $localPart
     */
    private function __construct($displayName, $domain, $localPart)
    {
        $this->displayName = $displayName;
        $this->domain = $domain;
        $this->localPart = $localPart;
    }

    /**
     * @param array $data
     *
     * @return Parts
     */
    public static function create(array $data)
    {
        return new self(
            (isset($data['display_name']) ? $data['display_name'] : null),
            (isset($data['domain']) ? $data['domain'] : null),
            (isset($data['local_part']) ? $data['local_part'] : null)
        );
    }

    /**
     * @return null|string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @return null|string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return null|string
     */
    public function getLocalPart()
    {
        return $this->localPart;
    }
}
