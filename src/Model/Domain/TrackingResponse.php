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
final class TrackingResponse implements ApiResponse
{
    private ClickTracking $click;
    private OpenTracking $open;
    private UnsubscribeTracking $unsubscribe;

    public static function create(array $data): ?self
    {
        if (!isset($data['tracking'])) {
            return null;
        }

        $trackingSettings = $data['tracking'];

        $model = new self();
        $model->click = ClickTracking::create($trackingSettings['click'] ?? []);
        $model->open = OpenTracking::create($trackingSettings['open'] ?? []);
        $model->unsubscribe = UnsubscribeTracking::create($trackingSettings['unsubscribe'] ?? []);

        return $model;
    }

    private function __construct()
    {
    }

    /**
     * @return ClickTracking
     */
    public function getClick(): ClickTracking
    {
        return $this->click;
    }

    /**
     * @return OpenTracking
     */
    public function getOpen(): OpenTracking
    {
        return $this->open;
    }

    /**
     * @return UnsubscribeTracking
     */
    public function getUnsubscribe(): UnsubscribeTracking
    {
        return $this->unsubscribe;
    }
}
