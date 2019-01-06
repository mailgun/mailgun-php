<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList\Member;

use Mailgun\Model\ApiResponse;

final class Member implements ApiResponse
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $address;

    /**
     * @var array
     */
    private $vars;

    /**
     * @var bool
     */
    private $subscribed;

    /**
     * @param array $data
     *
     * @return self
     */
    public static function create(array $data)
    {
        return new self(
            isset($data['name']) ? $data['name'] : null,
            isset($data['address']) ? $data['address'] : null,
            isset($data['vars']) ? $data['vars'] : [],
            isset($data['subscribed']) ? (bool) $data['subscribed'] : null
        );
    }

    private function __construct($name, $address, $vars = [], $subscribed = null)
    {
        $this->name = $name;
        $this->address = $address;
        $this->vars = $vars;
        $this->subscribed = $subscribed;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @return bool
     */
    public function isSubscribed()
    {
        return $this->subscribed;
    }
}
