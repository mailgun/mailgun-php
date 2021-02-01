<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\EmailValidationV4;

use Mailgun\Model\ApiResponse;

final class CreateBulkJobResponse implements ApiResponse
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $message;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();

        $model->id = $data['id'] ?? null;
        $model->message = $data['message'] ?? null;

        return $model;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
