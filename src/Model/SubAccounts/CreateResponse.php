<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\SubAccounts;

use Mailgun\Model\ApiResponse;

final class CreateResponse implements ApiResponse
{
    /**
     * @var array
     */
    private $message;

    /**
     * @var array
     */
    private $error;

    /**
     * @var SubAccount
     */
    private $item;

    private function __construct()
    {
    }

    /**
     * @param  array  $data
     * @return static
     */
    public static function create(array $data): self
    {
        $model = new self();
        $model->setItem(SubAccount::create($data['subaccount'] ?? []));

        if (isset($data['message'])) {
            $model->setMessage([$data['message']]);
        }

        return $model;
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }

    /**
     * @param array $message
     */
    public function setMessage(array $message): void
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getError(): array
    {
        return $this->error;
    }

    /**
     * @param array $error
     */
    public function setError(array $error): void
    {
        $this->error = $error;
    }

    public function setItem(SubAccount $subaccount): void
    {
        $this->item = $subaccount;
    }

    public function getItem(): SubAccount
    {
        return $this->item;
    }
}
