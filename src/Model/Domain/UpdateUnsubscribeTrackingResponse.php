<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Domain;

use Mailgun\Model\ApiResponse;

/**
 * @author Artem Bondarenko <artem@uartema.com>
 */
final class UpdateUnsubscribeTrackingResponse implements ApiResponse
{
    private ?string $message;
    private UnsubscribeTracking $unsubscribe;

    public static function create(array $data): self
    {
        $model = new self();
        $model->message = $data['message'] ?? null;
        $model->unsubscribe = UnsubscribeTracking::create($data['unsubscribe'] ?? []);

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return UnsubscribeTracking
     */
    public function getUnsubscribe(): UnsubscribeTracking
    {
        return $this->unsubscribe;
    }
}
