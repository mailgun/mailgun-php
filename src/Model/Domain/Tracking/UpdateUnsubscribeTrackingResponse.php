<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Domain\Tracking;

use Mailgun\Model\ApiResponse;

/**
 * @author Blake Hancock <blake@osim.digital>
 */
final class UpdateUnsubscribeTrackingResponse implements ApiResponse
{
    private $unsubscribe;
    private $message;

    public static function create(array $data): self
    {
        $model = new self();
        if (isset($data['unsubscribe'])) {
            $model->unsubscribe = UnsubscribeTrackingItem::create($data['unsubscribe']);
        }

        return $model;
    }

    private function __construct()
    {
    }

    public function getUnsubscribe(): ?UnsubscribeTrackingItem
    {
        return $this->unsubscribe;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
