<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Route;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class Action
{
    private $action;

    /**
     * Action Named Constructor to build several Action DTOs provided by an Array.
     *
     * @return Action[]
     */
    public static function createMultiple(array $data)
    {
        $items = [];

        foreach ($data as $action) {
            $items[] = new self($action);
        }

        return $items;
    }

    private function __construct(string $action)
    {
        $this->action = $action;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}
