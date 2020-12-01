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

final class GetBulkPreviewResponse implements ApiResponse
{
    /**
     * @var Preview
     */
    private $preview;

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();
        $model->preview = Preview::create($data['preview']);

        return $model;
    }

    public function getPreview(): Preview
    {
        return $this->preview;
    }
}
