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
final class UpdateOpenTrackingResponse implements ApiResponse
{
    private $open;
    private $message;

    public static function create(array $data): self
    {
        $model = new self();
        if (isset($data['open'])) {
            $model->open = OpenTrackingItem::create($data['open']);
        }

        return $model;
    }

    private function __construct()
    {
    }

    public function getOpen(): ?OpenTrackingItem
    {
        return $this->open;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
