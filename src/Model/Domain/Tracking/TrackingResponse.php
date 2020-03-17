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
final class TrackingResponse implements ApiResponse
{
    private $click;
    private $open;
    private $unsubscribe;

    public static function create(array $data): self
    {
        $click = null;
        $open = null;
        $unsubscribe = null;

        if (isset($data['tracking']['click'])) {
            $click = ClickTrackingItem::create($data['tracking']['click']);
        }

        if (isset($data['tracking']['open'])) {
            $open = OpenTrackingItem::create($data['tracking']['open']);
        }

        if (isset($data['tracking']['unsubscribe'])) {
            $unsubscribe = UnsubscribeTrackingItem::create($data['tracking']['unsubscribe']);
        }

        $model = new self();
        $model->open = $open;
        $model->click = $click;
        $model->unsubscribe = $unsubscribe;

        return $model;
    }

    private function __construct()
    {
    }

    public function getClick(): ?ClickTrackingItem
    {
        return $this->click;
    }

    public function getOpen(): ?OpenTrackingItem
    {
        return $this->open;
    }

    public function getUnsubscribe(): ?UnsubscribeTrackingItem
    {
        return $this->unsubscribe;
    }
}
