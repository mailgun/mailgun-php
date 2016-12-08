<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Resource\Api\Routes\Dto;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class ActionDto
{
    /**
     * @var string
     */
    private $action;

    /**
     * ActionDto Named Constructor to build several Action DTOs provided by an Array.
     *
     * @param array $data
     *
     * @return ActionDto[]
     */
    public static function createMultiple(array $data)
    {
        $items = [];

        foreach ($data as $action) {
            $items[] = new self($action);
        }

        return $items;
    }

    /**
     * ActionDto Private Constructor.
     *
     * @param $action
     */
    private function __construct($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
