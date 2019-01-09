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
    protected $paging;

    public function getNextUrl(): ?string
    {
        if (!isset($this->paging['next'])) {
            return null;
        }

        return $this->paging['next'];
    }

    public function getPreviousUrl(): ?string
    {
        if (!isset($this->paging['previous'])) {
            return null;
        }

        return $this->paging['previous'];
    }

    public function getFirstUrl(): ?string
    {
        if (!isset($this->paging['first'])) {
            return null;
        }

        return $this->paging['first'];
    }

    public function getLastUrl(): ?string
    {
        if (!isset($this->paging['last'])) {
            return null;
        }

        return $this->paging['last'];
    }
}
