<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\SubAccounts;


/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class SubAccount
{

    final private function __construct()
    {
    }

    /**
     * @throws \Exception
     */
    public static function create(array $data): self
    {
        return new static();
    }
}
