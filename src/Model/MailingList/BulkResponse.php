<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model\MailingList;

use Mailgun\Model\ApiResponse;

final class BulkResponse implements ApiResponse
{
    /**
     * @var array|null
     */
    private $list;

    /**
     * @var string|null
     */
    private $message;
    /**
     * @var string|null
     */
    private $taskId;

    public static function create(array $data): self
    {
        $model = new self();
        $model->list = $data['list'] ?? null;
        $model->message = $data['message'] ?? null;
        $model->taskId = $data['task-id'] ?? null;

        return $model;
    }

    /**
     * @return array|null
     */
    public function getList(): ?array
    {
        return $this->list;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return string|null
     */
    public function getTaskId(): ?string
    {
        return $this->taskId;
    }
}
