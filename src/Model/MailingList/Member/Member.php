<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList\Member;

use Mailgun\Model\ApiResponse;

final class Member implements ApiResponse
{
    private $name;
    private $address;
    private $vars;
    private $subscribed;

    public static function create(array $data): self
    {
        $model = new self();
        $model->name = $data['name'] ?? null;
        $model->address = $data['address'] ?? null;
        $model->vars = $data['vars'] ?? [];
        $model->subscribed = $data['subscribed'] ?? null;

        return $model;
    }

    private function __construct()
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getVars(): array
    {
        return $this->vars;
    }

    public function isSubscribed(): ?bool
    {
        return $this->subscribed;
    }
}
