<?php

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Exception;

use Mailgun\Exception;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class InvalidArgumentException extends \InvalidArgumentException implements Exception
{
}
