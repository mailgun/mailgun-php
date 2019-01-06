<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Ip;

use Mailgun\Model\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class ShowResponse implements ApiResponse
{
    /**
     * @var string
     */
    private $ip;

    /**
     * @var bool
     */
    private $dedicated;

    /**
     * @var string
     */
    private $rdns;

    private function __construct()
    {
    }

    public static function create(array $data)
    {
        $model = new self();
        $model->ip = $data['ip'];
        $model->dedicated = (bool) $data['dedicated'];
        $model->rdns = $data['rdns'];

        return $model;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return bool
     */
    public function getDedicated()
    {
        return $this->dedicated;
    }

    /**
     * @return string
     */
    public function getRdns()
    {
        return $this->rdns;
    }
}
