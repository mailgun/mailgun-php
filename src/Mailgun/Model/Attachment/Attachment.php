<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Attachment;

use Mailgun\Model\ApiResponse;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Attachment implements ApiResponse
{
    private $data;

    public static function create(array $data)
    {
        $new = new self();
        $new->data = $data;

        return $new;
    }

    public function getData()
    {
        return $this->data;
    }
}
