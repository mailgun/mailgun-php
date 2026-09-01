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
 * We proxy Webmozart\Assert because we want to throw our own Exception.
 *
 * A proxy is used to allow compatibility with version 1 and 2 of webmozart/assert.
 *
 * Explicitly specifying methods because using mixin breaks Psalm.
 * Psalm doesn't understand the mixed in class is from a library that can have a different minimum PHP version.
 * Can likely be replaced with a mixin once the minimum PHP version is bumped to PHP 8.0+.
 *
 * @method static array allString($value, $message = '')
 * @method static mixed boolean($value, $message = '')
 * @method static string fileExists($value, $message = '')
 * @method static mixed greaterThan($value, $limit, $message = '')
 * @method static mixed greaterThanEq($value, $limit, $message = '')
 * @method static mixed inArray($value, array $values, $message = '')
 * @method static string ip($value, $message = '')
 * @method static array isArray($value, $message = '')
 * @method static array isList($value, $message = '')
 * @method static array keyExists($array, $key, $message = '')
 * @method static string lengthBetween($value, $min, $max, $message = '')
 * @method static string maxLength($value, $max, $message = '')
 * @method static string minLength($value, $min, $message = '')
 * @method static mixed notEmpty($value, $message = '')
 * @method static array nullOrIsArray($value, $message = '')
 * @method static mixed nullOrString($value, $message = '')
 * @method static mixed nullOrStringNotEmpty($value, $message = '')
 * @method static mixed oneOf($value, array $values, $message = '')
 * @method static mixed range($value, $min, $max, $message = '')
 * @method static string regex($value, string $pattern, $message = '')
 * @method static string string($value, $message = '')
 * @method static string stringNotEmpty($value, $message = '')
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Assert
{
    /**
     * Proxy all static assertion calls to webmozart/assert.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments)
    {
        try {
            return \Webmozart\Assert\Assert::$name(...$arguments);
        } catch (\InvalidArgumentException $exception) { // @phpstan-ignore catch.neverThrown
            throw new InvalidArgumentException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
