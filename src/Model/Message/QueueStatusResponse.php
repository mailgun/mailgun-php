<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\Message;

use Mailgun\Model\ApiResponse;

final class QueueStatusResponse implements ApiResponse
{
    /**
     * @var array
     */
    private $regular;
    /**
     * @var array
     */
    private $scheduled;

    public static function create(array $data): self
    {
        $model = new self();
        $model->regular = $data['regular'] ?? [];
        $model->scheduled = $data['scheduled'] ?? [];

        return $model;
    }

    /**
     * @return array
     */
    public function getRegular(): array
    {
        return $this->regular;
    }

    /**
     * @return array
     */
    public function getScheduled(): array
    {
        return $this->scheduled;
    }

}
