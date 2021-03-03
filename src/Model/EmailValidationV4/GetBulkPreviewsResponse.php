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

final class GetBulkPreviewsResponse implements ApiResponse
{
    /**
     * @var array
     */
    private $previews = [];

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();

        $previews = [];

        if (isset($data['previews'])) {
            foreach ($data['previews'] as $job) {
                $previews[] = Preview::create($job);
            }
        }

        $model->previews = $previews;

        return $model;
    }

    public function getPreviews(): array
    {
        return $this->previews;
    }
}
