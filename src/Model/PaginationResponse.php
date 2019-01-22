<?php

declare(strict_types=1);

/*
 * Copyright (C) 2013 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Model;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
trait PaginationResponse
{
    /**
     * @var array
     */
    private $paging;

    public function getNextUrl(): ?string
    {
        return $this->paging['next'] ?? null;
    }

    public function getPreviousUrl(): ?string
    {
        return $this->paging['previous'] ?? null;
    }

    public function getFirstUrl(): ?string
    {
        return $this->paging['first'] ?? null;
    }

    public function getLastUrl(): ?string
    {
        return $this->paging['last'] ?? null;
    }
}
