<?php

namespace Mailgun\Resource;

interface CreatableFromArray
{
    /**
     * @param array $data
     *
     * @return self
     */
    public static function createFromArray(array $data);
}
