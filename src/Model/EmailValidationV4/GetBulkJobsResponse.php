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
use Mailgun\Model\PaginationResponse;

final class GetBulkJobsResponse implements ApiResponse
{
    use PaginationResponse;

    /**
     * @var int
     */
    private $total = 0;

    /**
     * @var array
     */
    private $jobs = [];

    private function __construct()
    {
    }

    public static function create(array $data): self
    {
        $model = new self();

        $jobs = [];

        if (isset($data['jobs'])) {
            foreach ($data['jobs'] as $job) {
                $jobs[] = Job::create($job);
            }
        }

        $model->jobs = $jobs;
        $model->total = $data['total'] ?? 0;
        $model->paging = $data['paging'];

        return $model;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getJobs(): array
    {
        return $this->jobs;
    }
}
