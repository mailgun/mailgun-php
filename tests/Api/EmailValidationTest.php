<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Tests\Api;

use Mailgun\Api\EmailValidation;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
class EmailValidationTest extends TestCase
{
    protected function getApiClass()
    {
        return EmailValidation::class;
    }
}
