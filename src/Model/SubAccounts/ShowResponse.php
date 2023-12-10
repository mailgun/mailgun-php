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

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
final class ShowResponse implements ApiResponse
{
    /**
     * @var SubAccount
     */
    private $item;

    /**
     * @var array
     */
    private $message;

    /**
     * @param  array        $data
     * @return ShowResponse
     * @throws \Exception
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
     * @return SubAccount
     */
    public function getItem(): SubAccount
    {
        return $this->item;
    }

    /**
     * @param SubAccount $item
     */
    public function setItem(SubAccount $item): void
    {
        $this->item = $item;
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
}
