<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\EmailValidation;

/**
 * @author David Garcia <me@davidgarcia.cat>
 */
final class Parts
{
    /**
     * @var string|null
     */
    private $displayName;

    /**
     * @var string|null
     */
    private $domain;

    /**
     * @var string|null
     */
    private $localPart;

    private function __construct()
    {
    }

    /**
     * @return Parts
     */
    public static function create(array $data)
    {
        $model = new self();
        $model->displayName = $data['display_name'] ?? null;
        $model->domain = $data['domain'] ?? null;
        $model->localPart = $data['local_part'] ?? null;

        return $model;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function getLocalPart(): ?string
    {
        return $this->localPart;
    }
}
