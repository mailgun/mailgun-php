<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun;

use Mailgun\Exception\InvalidArgumentException;

/**
 * We need to override Webmozart\Assert because we want to throw our own Exception.
 *
 * Webmozart\Assert version 2.0 is required for PHP 8.4 support, but
 * incompatible with versions 1.x. So unfortunately we need this conditional
 * class definition.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
if (version_compare(\Composer\InstalledVersions::getVersion('webmozart/assert'), '2.0.0', '>=')) {
  final class Assert extends \Webmozart\Assert\Assert
  {
    protected static function reportInvalidArgument(string $message): never
    {
      throw new InvalidArgumentException($message);
    }
  }
} else {
  final class Assert extends \Webmozart\Assert\Assert
  {
    protected static function reportInvalidArgument($message): void
    {
      throw new InvalidArgumentException($message);
    }
  }
}
